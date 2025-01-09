<?php
include_once 'models/Role.php';
include_once 'models/Permission.php';
include_once 'services/ExcelExportService.php';
use App\Services\ExcelExportService;
class RoleController extends Controller
{
    private $role;
    private $permission;

    public function __construct()
    {
        $this->role = new Role();
        $this->permission = new Permission();
    }

    public function index()
    {
        $roles = $this->role->read();
        $content = 'views/roles/index.php';
        include('views/layouts/base.php');
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Lưu trữ dữ liệu form vào session để giữ lại khi có lỗi
                $_SESSION['form_data'] = [
                    'name' => $_POST['name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'permissions' => $_POST['permissions'] ?? []
                ];
    
                // Xử lý dữ liệu cơ bản của role
                foreach (['name', 'description'] as $field) {
                    if (property_exists($this->role, $field) && isset($_POST[$field])) {
                        $this->role->$field = strip_tags(trim($_POST[$field]));
                    }
                }
                if ($this->role->create()) {
                    foreach ($_POST['permissions'] as $permission_id) {
                        $this->permission->assignPermission($this->role->role_id,$permission_id);
                    }
                    $_SESSION['message'] = 'Role created successfully!';
                    $_SESSION['message_type'] = 'success';
                    unset($_SESSION['form_data']);
                    header("Location: index.php?model=role&action=index");
                    exit();
                } else {
                    throw new Exception('Failed to create role');
                }
    
            } catch (Exception $e) {
                $_SESSION['message'] = 'Role creation failed: ' . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
                
                $data = $this->permission->read();
                $content = 'views/roles/create.php';
                include('views/layouts/base.php');
                return;
            }
        }
        $data = $this->permission->read();
        $content = 'views/roles/create.php';
        include('views/layouts/base.php');
    }

    public function edit($id) 
    {
        // First check if the role exists
        $role = $this->role->readById($id);
        if (!$role) {
            $_SESSION['message'] = 'Role not found!';
            $_SESSION['message_type'] = 'danger';
            header("Location: index.php?model=role&action=index");
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($_POST as $key => $value) {
                    if (property_exists($this->role, $key)) {
                        $this->role->$key = strip_tags(trim($value));
                    }
                }
                if ($this->role->update($id)) {
                    $this->role->removeAllPermissions($id);
                    // Check if permissions were selected
                    if (isset($_POST['permissions']) && is_array($_POST['permissions'])) {
                        foreach ($_POST['permissions'] as $permission_id) {
                            $this->permission->assignPermission($id, $permission_id);
                        }
                    }
                    $_SESSION['message'] = 'Role updated successfully!';
                    $_SESSION['message_type'] = 'success';
                    header("Location: index.php?model=role&action=index");
                    exit();
                } else {
                    throw new Exception('Failed to update role');
                }
            } catch (Exception $e) {
                $_SESSION['message'] = 'Role update failed. Please try again!' . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
        $this->role->role_id=$id;
        $_SESSION['form_data'] = [
            'role_id'=>$id,
            'name' => $role['name'] ?? '',
            'description' => $role['description'] ?? '',
            'permissions' => $this->role->getPermissions() ?? []
        ];
        // var_dump($this->role->getPermissions());
        // exit();
        $data = $this->permission->read();
        $content = 'views/roles/edit.php';
        include('views/layouts/base.php');
    }
    public function delete($id)
    {
        try {
            if ($this->role->hasUsers($id)) {
                throw new Exception('Cannot delete role: There are users assigned to this role.');
            }

            if ($this->role->delete($id)) {
                $_SESSION['message'] = 'Role deleted successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                throw new Exception('Failed to delete role!');
            }
        } catch (Exception $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header("Location: index.php?model=role&action=index");
        exit();
    }
    public function export() 
    {
        $excelService = new ExcelExportService();
        $roles = $this->role->read();
        
       
        $headers = [
            'role_id' => 'ID',
            'name' => 'Tên vai trò',
            'description' => 'Mô tả',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật'
        ];

        $processedData = [];
        foreach ($roles as $role) {
            $row = [];
            foreach (array_keys($headers) as $key) {
                $row[$key] = $role[$key] ?? '';
            }

            $this->role->role_id = $role['role_id'];
            $permissions = $this->role->getPermissions();
            $permissionNames = array_column($permissions, 'name');
            $row['permissions'] = implode(', ', $permissionNames);
            
            $processedData[] = $row;
        }

        $headers['permissions'] = 'Quyền hạn';

        $config = [
            'headers' => $headers,
            'data' => $processedData,
            'filename' => 'danh_sach_vai_tro.xlsx',
            'headerStyle' => [
                'font' => [
                    'bold' => true
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E2E8F0'
                    ]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ]
        ];
        
        $excelService->exportWithConfig($config);
    }
}