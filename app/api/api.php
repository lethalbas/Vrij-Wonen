<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . "/../controller/cities_controller.php";
require_once __DIR__ . "/../controller/objects_controller.php";
require_once __DIR__ . "/../controller/properties_controller.php";
require_once __DIR__ . "/../controller/staff_controller.php";
require_once __DIR__ . "/../controller/inquiries_controller.php";

// Get the current request URI and method
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove query string from URI for parsing
$request_uri = parse_url($request_uri, PHP_URL_PATH);

// Parse the URI to get the endpoint and parameters
$uri_parts = explode('/', trim($request_uri, '/'));
// Remove empty parts
$uri_parts = array_filter($uri_parts, function($part) { return $part !== ''; });
$uri_parts = array_values($uri_parts); // Re-index array

$endpoint = $uri_parts[0] ?? ''; // 'api'
$resource = $uri_parts[1] ?? ''; // 'cities', 'objects', etc.
$id = $uri_parts[2] ?? null;    // ID for specific resource

// Helper function to send JSON response
function sendResponse($data, $status_code = 200) {
    http_response_code($status_code);
    echo json_encode($data);
    exit();
}

// Helper function to send error response
function sendError($message, $status_code = 400) {
    sendResponse(['error' => $message], $status_code);
}

// Route API requests
try {
    switch ($resource) {
        case 'cities':
            $controller = new cities_controller();
            switch ($request_method) {
                case 'GET':
                    if ($id) {
                        // Get specific city
                        $city = $controller->get_by_id($id);
                        if ($city) {
                            sendResponse($city);
                        } else {
                            sendError('City not found', 404);
                        }
                    } else {
                        // Get all cities or check for 'used' parameter
                        if (isset($_GET['used']) && $_GET['used'] === 'true') {
                            $cities = $controller->get_all_used();
                        } else {
                            $cities = $controller->get_all();
                        }
                        sendResponse($cities);
                    }
                    break;
                case 'POST':
                    // Create new city
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->create($input);
                        if ($result) {
                            sendResponse($result, 201);
                        } else {
                            sendError('Failed to create city', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                case 'PUT':
                    // Update city
                    if (!$id) {
                        sendError('City ID required for update', 400);
                    }
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->update($id, $input);
                        if ($result) {
                            sendResponse($result);
                        } else {
                            sendError('Failed to update city', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                default:
                    sendError('Method not allowed', 405);
            }
            break;

        case 'objects':
            $controller = new objects_controller();
            switch ($request_method) {
                case 'GET':
                    if ($id) {
                        // Get specific object
                        $object = $controller->get_by_id($id);
                        if ($object) {
                            sendResponse($object);
                        } else {
                            sendError('Object not found', 404);
                        }
                    } else {
                        // Get all objects or filtered
                        if (!empty($_GET)) {
                            // Apply filters
                            $filters = [];
                            if (isset($_GET['city'])) {
                                $filters['cityid'] = (int)$_GET['city'];
                            }
                            if (isset($_GET['properties'])) {
                                $properties = explode(',', $_GET['properties']);
                                $filters['properties'] = array_map('intval', $properties);
                            }
                            $objects = $controller->get_all_filtered($filters);
                        } else {
                            $objects = $controller->get_all();
                        }
                        sendResponse($objects);
                    }
                    break;
                case 'POST':
                    // Create new object
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->create($input);
                        if ($result) {
                            sendResponse($result, 201);
                        } else {
                            sendError('Failed to create object', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                case 'PUT':
                    // Update object
                    if (!$id) {
                        sendError('Object ID required for update', 400);
                    }
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->update($id, $input);
                        if ($result) {
                            sendResponse($result);
                        } else {
                            sendError('Failed to update object', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                case 'DELETE':
                    // Delete object
                    if (!$id) {
                        sendError('Object ID required for deletion', 400);
                    }
                    $result = $controller->delete($id);
                    if ($result) {
                        sendResponse(['message' => 'Object deleted successfully']);
                    } else {
                        sendError('Failed to delete object', 500);
                    }
                    break;
                default:
                    sendError('Method not allowed', 405);
            }
            break;

        case 'properties':
            $controller = new properties_controller();
            switch ($request_method) {
                case 'GET':
                    if ($id) {
                        // Get specific property
                        $property = $controller->get_by_id($id);
                        if ($property) {
                            sendResponse($property);
                        } else {
                            sendError('Property not found', 404);
                        }
                    } else {
                        // Get all properties
                        $properties = $controller->get_all();
                        sendResponse($properties);
                    }
                    break;
                case 'POST':
                    // Create new property
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->create($input);
                        if ($result) {
                            sendResponse($result, 201);
                        } else {
                            sendError('Failed to create property', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                case 'PUT':
                    // Update property
                    if (!$id) {
                        sendError('Property ID required for update', 400);
                    }
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->update($id, $input);
                        if ($result) {
                            sendResponse($result);
                        } else {
                            sendError('Failed to update property', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                default:
                    sendError('Method not allowed', 405);
            }
            break;

        case 'staff':
            $controller = new staff_controller();
            switch ($request_method) {
                case 'GET':
                    if ($id) {
                        // Get specific staff member
                        $staff = $controller->get_by_id($id);
                        if ($staff) {
                            sendResponse($staff);
                        } else {
                            sendError('Staff member not found', 404);
                        }
                    } else {
                        // Get all staff members or filtered
                        if (isset($_GET['admins']) && $_GET['admins'] === 'true') {
                            $staff = $controller->get_admins();
                        } elseif (isset($_GET['non_admins']) && $_GET['non_admins'] === 'true') {
                            $staff = $controller->get_non_admins();
                        } else {
                            $staff = $controller->get_all();
                        }
                        sendResponse($staff);
                    }
                    break;
                case 'POST':
                    // Create new staff member
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->create($input);
                        if ($result) {
                            sendResponse($result, 201);
                        } else {
                            sendError('Failed to create staff member', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                case 'PUT':
                    // Update staff member
                    if (!$id) {
                        sendError('Staff ID required for update', 400);
                    }
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->update($id, $input);
                        if ($result) {
                            sendResponse($result);
                        } else {
                            sendError('Failed to update staff member', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                case 'DELETE':
                    // Delete staff member
                    if (!$id) {
                        sendError('Staff ID required for deletion', 400);
                    }
                    $result = $controller->delete($id);
                    if ($result) {
                        sendResponse(['message' => 'Staff member deleted successfully']);
                    } else {
                        sendError('Failed to delete staff member', 500);
                    }
                    break;
                default:
                    sendError('Method not allowed', 405);
            }
            break;

        case 'inquiries':
            $controller = new inquiries_controller();
            switch ($request_method) {
                case 'GET':
                    if ($id) {
                        // Get specific inquiry
                        $inquiry = $controller->get_by_id($id);
                        if ($inquiry) {
                            sendResponse($inquiry);
                        } else {
                            sendError('Inquiry not found', 404);
                        }
                    } else {
                        // Get all inquiries or filtered
                        if (isset($_GET['unhandled']) && $_GET['unhandled'] === 'true') {
                            $inquiries = $controller->get_unhandled();
                        } elseif (isset($_GET['handled']) && $_GET['handled'] === 'true') {
                            $inquiries = $controller->get_handled();
                        } elseif (isset($_GET['with_object_info']) && $_GET['with_object_info'] === 'true') {
                            $inquiries = $controller->get_inquiries_with_object_info();
                        } else {
                            $inquiries = $controller->get_all();
                        }
                        sendResponse($inquiries);
                    }
                    break;
                case 'POST':
                    // Create new inquiry
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->create($input);
                        if ($result) {
                            sendResponse($result, 201);
                        } else {
                            sendError('Failed to create inquiry', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                case 'PUT':
                    // Update inquiry
                    if (!$id) {
                        sendError('Inquiry ID required for update', 400);
                    }
                    $input = json_decode(file_get_contents('php://input'), true);
                    if ($input) {
                        $result = $controller->update($id, $input);
                        if ($result) {
                            sendResponse($result);
                        } else {
                            sendError('Failed to update inquiry', 500);
                        }
                    } else {
                        sendError('Invalid JSON input', 400);
                    }
                    break;
                case 'PATCH':
                    // Special PATCH endpoints for inquiry actions
                    if ($id) {
                        if (isset($_GET['action'])) {
                            switch ($_GET['action']) {
                                case 'toggle_handled':
                                    $result = $controller->toggle_handled($id);
                                    if ($result) {
                                        sendResponse($result);
                                    } else {
                                        sendError('Failed to toggle handled status', 500);
                                    }
                                    break;
                                case 'complete':
                                    $result = $controller->complete($id);
                                    if ($result) {
                                        sendResponse($result);
                                    } else {
                                        sendError('Failed to complete inquiry', 500);
                                    }
                                    break;
                                default:
                                    sendError('Invalid action', 400);
                            }
                        } else {
                            sendError('Action parameter required for PATCH', 400);
                        }
                    } else {
                        sendError('Inquiry ID required for PATCH', 400);
                    }
                    break;
                default:
                    sendError('Method not allowed', 405);
            }
            break;

        default:
            sendError('Resource not found', 404);
    }
} catch (Exception $e) {
    sendError('Internal server error: ' . $e->getMessage(), 500);
}
?>
