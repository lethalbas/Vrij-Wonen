<?php
session_start();

// Handle authentication BEFORE any HTML output
require_once __DIR__ . "/../../util/dependencies_util.php"; 
$dep = new dependencies_util();
$dep->all_dependencies();
$file_handler_util = new file_handler_util();
$ulsu = new user_login_session_util();

// Check login status and api_admin role
$status = $ulsu->get_login_status();
if($status < 1){
    header('Location: /forbidden'); 
    exit;
}

// Check if user has api_admin role
if(!$ulsu->has_role('api_admin')){
    header('Location: /forbidden'); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentatie | Vrij Wonen</title>
    <link rel="stylesheet" href="<?= $file_handler_util->get_cdn_style_dir(); ?>/api_documentation.css">
</head>
<body>
    <?php require_once __DIR__ . "/../header.php"; ?>
    <div class="container mb-5">
        <div class="row">
            <div class="col-12">
                <div class="text-center mt-5 mb-4">
                    <h1>API Documentatie</h1>
                    <p class="lead">Complete REST API voor Vrij Wonen applicatie</p>
                    <button type="button" class="btn btn-secondary" onclick="window.location='/beheerder';">← Terug naar Dashboard</button>
                </div>
                
                <!-- API Overview -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>API Overzicht</h3>
                    </div>
                    <div class="card-body">
                        <p>De Vrij Wonen API biedt toegang tot alle data van de applicatie via REST endpoints. Alle responses zijn in JSON formaat.</p>
                        <p><strong>Base URL:</strong> <code>http://localhost:8080/api</code></p>
                        <p><strong>Content-Type:</strong> <code>application/json</code></p>
                        <p><strong>CORS:</strong> Ondersteund voor cross-origin requests</p>
                        <p><strong>Authenticatie:</strong> HTTP Basic Auth of JSON Login endpoint vereist voor beschermde endpoints</p>
                    </div>
                </div>

                <!-- Authentication -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>🔐 Authenticatie</h3>
                    </div>
                    <div class="card-body">
                        <p>De API gebruikt een rollen-gebaseerd authenticatiesysteem. Alleen gebruikers met de juiste rollen hebben toegang tot beschermde endpoints.</p>
                        
                        <h5>Rollen Systeem:</h5>
                        <ul>
                            <li><strong>api_admin</strong> (priority 1) - API Administrator - API access only</li>
                            <li><strong>system_admin</strong> (priority 2) - System Administrator - Full system access</li>
                            <li><strong>admin</strong> (priority 3) - Administrator - Full application access</li>
                            <li><strong>medewerker</strong> (priority 4) - Medewerker - Standard employee access</li>
                            <li><strong>archived</strong> (priority 5) - Gearchiveerde medewerker - Geen toegang tot beheersfuncties</li>
                        </ul>
                        
                        <p><strong>Belangrijk:</strong> Gebruikers met hogere priority rollen krijgen automatisch toegang tot lagere priority rollen.</p>

                        <h5>Authenticatie Methoden:</h5>
                        
                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/auth</strong>
                            <p class="mb-2">Haal huidige gebruiker informatie op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/auth')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-post">
                            <span class="method-badge badge-post">POST</span>
                            <strong>/api/auth</strong>
                            <p class="mb-2">Login met username/password (JSON)</p>
                            <button class="copy-btn" onclick="copyToClipboard('POST /api/auth')">Kopieer</button>
                        </div>

                        <h5>1. HTTP Basic Auth:</h5>
                        <div class="code-block">
# Header toevoegen aan requests
Authorization: Basic &lt;base64(username:password)&gt;

# cURL voorbeeld
curl -u username:password http://localhost:8080/api/staff
                        </div>

                        <h5>2. JSON Login Endpoint:</h5>
                        <div class="code-block">
# POST request naar /api/auth
{
  "username": "admin",
  "password": "password"
}

# cURL voorbeeld
curl -X POST http://localhost:8080/api/auth \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
                        </div>

                        <h5>Response Voorbeelden:</h5>
                        <div class="code-block">
# Succesvolle login response
{
  "user": {
    "id": 1,
    "username": "admin",
    "email": "admin@vrijwonen.nl",
    "admin": 1,
    "roles": [
      {
        "id": 1,
        "name": "api_admin",
        "description": "API Administrator - API access only",
        "priority": 1
      },
      {
        "id": 2,
        "name": "system_admin",
        "description": "System Administrator - Full system access",
        "priority": 2
      },
      {
        "id": 3,
        "name": "admin", 
        "description": "Administrator - Full application access",
        "priority": 3
      }
    ]
  },
  "authenticated": true,
  "message": "Login successful"
}

# Niet geauthenticeerd
{
  "authenticated": false
}
                        </div>

                        <h5>Publieke vs Beschermde Endpoints:</h5>
                        <table class="parameter-table">
                            <thead>
                                <tr>
                                    <th>Endpoint</th>
                                    <th>Methoden</th>
                                    <th>Authenticatie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>/api/cities</td>
                                    <td>GET</td>
                                    <td>❌ Publiek</td>
                                </tr>
                                <tr>
                                    <td>/api/properties</td>
                                    <td>GET</td>
                                    <td>❌ Publiek</td>
                                </tr>
                                <tr>
                                    <td>/api/objects</td>
                                    <td>GET</td>
                                    <td>❌ Publiek</td>
                                </tr>
                                <tr>
                                    <td>/api/auth</td>
                                    <td>GET, POST</td>
                                    <td>❌ Publiek</td>
                                </tr>
                                <tr>
                                    <td>Alle andere endpoints</td>
                                    <td>Alle</td>
                                    <td>✅ Vereist</td>
                                </tr>
                            </tbody>
                        </table>

                        <h5>Error Responses:</h5>
                        <div class="code-block">
# 401 Unauthorized (geen authenticatie)
{
  "error": "Authentication required"
}

# 403 Forbidden (onvoldoende rechten)
{
  "error": "Insufficient permissions"
}
                        </div>
                    </div>
                </div>

                <!-- Cities API -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>🏙️ Cities API</h3>
                    </div>
                    <div class="card-body">
                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/cities</strong>
                            <p class="mb-2">Haal alle steden op (300 Nederlandse gemeenten)</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/cities')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/cities?used=true</strong>
                            <p class="mb-2">Haal alleen steden op die gebruikt worden door objecten</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/cities?used=true')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/cities/{id}</strong>
                            <p class="mb-2">Haal specifieke stad op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/cities/17')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-post">
                            <span class="method-badge badge-post">POST</span>
                            <strong>/api/cities</strong>
                            <p class="mb-2">Maak nieuwe stad aan</p>
                            <button class="copy-btn" onclick="copyToClipboard('POST /api/cities')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-put">
                            <span class="method-badge badge-put">PUT</span>
                            <strong>/api/cities/{id}</strong>
                            <p class="mb-2">Update bestaande stad</p>
                            <button class="copy-btn" onclick="copyToClipboard('PUT /api/cities/17')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-delete">
                            <span class="method-badge badge-delete">DELETE</span>
                            <strong>/api/cities/{id}</strong>
                            <p class="mb-2">Verwijder stad (alleen als niet in gebruik)</p>
                            <button class="copy-btn" onclick="copyToClipboard('DELETE /api/cities/17')">Kopieer</button>
                        </div>

                        <h5>POST/PUT Request Body:</h5>
                        <div class="code-block">
{
  "citiename": "Nieuwe Stad"
}
                        </div>

                        <h5>Response Voorbeeld:</h5>
                        <div class="code-block">
[
  {
    "id": 17,
    "citiename": "Amsterdam"
  },
  {
    "id": 106,
    "citiename": "'s-Gravenhage"
  }
]
                        </div>
                    </div>
                </div>

                <!-- Objects API -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>🏠 Objects API</h3>
                    </div>
                    <div class="card-body">
                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/objects</strong>
                            <p class="mb-2">Haal alle objecten op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/objects')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/objects?city={id}</strong>
                            <p class="mb-2">Filter objecten op stad</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/objects?city=17')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/objects?properties={id1,id2}</strong>
                            <p class="mb-2">Filter objecten op eigenschappen</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/objects?properties=1,2')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/objects/{id}</strong>
                            <p class="mb-2">Haal specifiek object op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/objects/1')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-post">
                            <span class="method-badge badge-post">POST</span>
                            <strong>/api/objects</strong>
                            <p class="mb-2">Maak nieuw object aan</p>
                            <button class="copy-btn" onclick="copyToClipboard('POST /api/objects')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-put">
                            <span class="method-badge badge-put">PUT</span>
                            <strong>/api/objects/{id}</strong>
                            <p class="mb-2">Update bestaand object</p>
                            <button class="copy-btn" onclick="copyToClipboard('PUT /api/objects/1')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-delete">
                            <span class="method-badge badge-delete">DELETE</span>
                            <strong>/api/objects/{id}</strong>
                            <p class="mb-2">Verwijder object</p>
                            <button class="copy-btn" onclick="copyToClipboard('DELETE /api/objects/1')">Kopieer</button>
                        </div>
                        
                        <h5>Query Parameters:</h5>
                        <table class="parameter-table">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Type</th>
                                    <th>Beschrijving</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>city</td>
                                    <td>integer</td>
                                    <td>Filter op stad ID</td>
                                </tr>
                                <tr>
                                    <td>properties</td>
                                    <td>string</td>
                                    <td>Comma-gescheiden lijst van eigenschap IDs</td>
                                </tr>
                            </tbody>
                        </table>

                        <h5>POST/PUT Request Body:</h5>
                        <div class="code-block">
{
  "title": "Moderne eengezinswoning",
  "price": "425000.00",
  "adress": "Hoofdstraat 123",
  "cityid": 17,
  "mainimage": "house_default"
}
                        </div>

                        <h5>Response Voorbeeld:</h5>
                        <div class="code-block">
[
  {
    "id": 1,
    "title": "Moderne eengezinswoning",
    "price": "425000.00",
    "adress": "Hoofdstraat 123",
    "citiename": "Amsterdam",
    "mainimage": "house_default"
  }
]
                        </div>
                    </div>
                </div>

                <!-- Properties API -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>🏷️ Properties API</h3>
                    </div>
                    <div class="card-body">
                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/properties</strong>
                            <p class="mb-2">Haal alle eigenschappen op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/properties')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/properties/{id}</strong>
                            <p class="mb-2">Haal specifieke eigenschap op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/properties/1')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-post">
                            <span class="method-badge badge-post">POST</span>
                            <strong>/api/properties</strong>
                            <p class="mb-2">Maak nieuwe eigenschap aan</p>
                            <button class="copy-btn" onclick="copyToClipboard('POST /api/properties')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-put">
                            <span class="method-badge badge-put">PUT</span>
                            <strong>/api/properties/{id}</strong>
                            <p class="mb-2">Update bestaande eigenschap</p>
                            <button class="copy-btn" onclick="copyToClipboard('PUT /api/properties/1')">Kopieer</button>
                        </div>

                        <h5>POST/PUT Request Body:</h5>
                        <div class="code-block">
{
  "propertie": "Nieuwe Eigenschap"
}
                        </div>

                        <h5>Response Voorbeeld:</h5>
                        <div class="code-block">
[
  {
    "id": 1,
    "propertie": "Garden"
  },
  {
    "id": 2,
    "propertie": "Garage"
  }
]
                        </div>
                    </div>
                </div>

                <!-- Staff API -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>👥 Staff API</h3>
                    </div>
                    <div class="card-body">
                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/staff</strong>
                            <p class="mb-2">Haal alle medewerkers op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/staff')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/staff?admins=true</strong>
                            <p class="mb-2">Haal alleen admins op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/staff?admins=true')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/staff?non_admins=true</strong>
                            <p class="mb-2">Haal alleen niet-admins op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/staff?non_admins=true')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/staff?manageable=true</strong>
                            <p class="mb-2">Haal alleen beheerbare gebruikers op (gebaseerd op huidige gebruiker)</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/staff?manageable=true')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/staff/{id}</strong>
                            <p class="mb-2">Haal specifieke medewerker op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/staff/1')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-post">
                            <span class="method-badge badge-post">POST</span>
                            <strong>/api/staff</strong>
                            <p class="mb-2">Maak nieuwe medewerker aan</p>
                            <button class="copy-btn" onclick="copyToClipboard('POST /api/staff')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-put">
                            <span class="method-badge badge-put">PUT</span>
                            <strong>/api/staff/{id}</strong>
                            <p class="mb-2">Update bestaande medewerker</p>
                            <button class="copy-btn" onclick="copyToClipboard('PUT /api/staff/1')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-delete">
                            <span class="method-badge badge-delete">DELETE</span>
                            <strong>/api/staff/{id}</strong>
                            <p class="mb-2">Verwijder medewerker</p>
                            <button class="copy-btn" onclick="copyToClipboard('DELETE /api/staff/1')">Kopieer</button>
                        </div>

                        <h5>POST/PUT Request Body:</h5>
                        <div class="code-block">
{
  "username": "nieuwe_gebruiker",
  "email": "gebruiker@vrijwonen.nl",
  "password": "wachtwoord123",
  "admin": 0
}
                        </div>

                        <h5>Response Voorbeeld:</h5>
                        <div class="code-block">
[
  {
    "id": 1,
    "username": "admin",
    "email": "admin@vrijwonen.nl",
    "admin": 1,
    "roles": [
      {
        "id": 1,
        "name": "api_admin",
        "description": "API Administrator - API access only",
        "priority": 1,
        "assigned_at": "2025-01-07 15:30:00"
      },
      {
        "id": 2,
        "name": "system_admin",
        "description": "System Administrator - Full system access",
        "priority": 2,
        "assigned_at": "2025-01-07 15:30:00"
      }
    ]
  }
]
                        </div>
                    </div>
                </div>

                <!-- Roles API -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>🔐 Roles API</h3>
                    </div>
                    <div class="card-body">
                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/roles</strong>
                            <p class="mb-2">Haal alle rollen op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/roles')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/roles?assignable=true</strong>
                            <p class="mb-2">Haal alleen toewijsbare rollen op (gebaseerd op huidige gebruiker)</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/roles?assignable=true')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/roles/{id}</strong>
                            <p class="mb-2">Haal specifieke rol op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/roles/1')">Kopieer</button>
                        </div>

                        <h5>Response Voorbeeld:</h5>
                        <div class="code-block">
[
  {
    "id": 1,
    "name": "api_admin",
    "description": "API Administrator - API access only",
    "priority": 1
  },
  {
    "id": 2,
    "name": "system_admin",
    "description": "System Administrator - Full system access",
    "priority": 2
  }
]
                        </div>
                    </div>
                </div>

                <!-- User Roles API -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>👤 User Roles API</h3>
                    </div>
                    <div class="card-body">
                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/user-roles/{user_id}</strong>
                            <p class="mb-2">Haal rollen van specifieke gebruiker op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/user-roles/1')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-post">
                            <span class="method-badge badge-post">POST</span>
                            <strong>/api/user-roles</strong>
                            <p class="mb-2">Wijs rol toe aan gebruiker</p>
                            <button class="copy-btn" onclick="copyToClipboard('POST /api/user-roles')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-delete">
                            <span class="method-badge badge-delete">DELETE</span>
                            <strong>/api/user-roles/{user_id}</strong>
                            <p class="mb-2">Verwijder rol van gebruiker</p>
                            <button class="copy-btn" onclick="copyToClipboard('DELETE /api/user-roles/1')">Kopieer</button>
                        </div>

                        <h5>POST Request Body:</h5>
                        <div class="code-block">
{
  "user_id": 2,
  "role_id": 3
}
                        </div>

                        <h5>DELETE Request Body:</h5>
                        <div class="code-block">
{
  "role_id": 3
}
                        </div>

                        <h5>Response Voorbeeld:</h5>
                        <div class="code-block">
[
  {
    "id": 1,
    "name": "api_admin",
    "description": "API Administrator - API access only",
    "priority": 1,
    "assigned_at": "2025-01-07 15:30:00"
  }
]
                        </div>
                    </div>
                </div>

                <!-- Inquiries API -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>📧 Inquiries API</h3>
                    </div>
                    <div class="card-body">
                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/inquiries</strong>
                            <p class="mb-2">Haal alle contactaanvragen op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/inquiries')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/inquiries?unhandled=true</strong>
                            <p class="mb-2">Haal alleen onbehandelde aanvragen op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/inquiries?unhandled=true')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/inquiries?handled=true</strong>
                            <p class="mb-2">Haal alleen behandelde aanvragen op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/inquiries?handled=true')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/inquiries?with_object_info=true</strong>
                            <p class="mb-2">Haal aanvragen op met object informatie</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/inquiries?with_object_info=true')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-get">
                            <span class="method-badge badge-get">GET</span>
                            <strong>/api/inquiries/{id}</strong>
                            <p class="mb-2">Haal specifieke contactaanvraag op</p>
                            <button class="copy-btn" onclick="copyToClipboard('GET /api/inquiries/1')">Kopieer</button>
                        </div>
                        
                        <div class="api-endpoint method-post">
                            <span class="method-badge badge-post">POST</span>
                            <strong>/api/inquiries</strong>
                            <p class="mb-2">Maak nieuwe contactaanvraag</p>
                            <button class="copy-btn" onclick="copyToClipboard('POST /api/inquiries')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-put">
                            <span class="method-badge badge-put">PUT</span>
                            <strong>/api/inquiries/{id}</strong>
                            <p class="mb-2">Update bestaande contactaanvraag</p>
                            <button class="copy-btn" onclick="copyToClipboard('PUT /api/inquiries/1')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-patch">
                            <span class="method-badge badge-patch">PATCH</span>
                            <strong>/api/inquiries/{id}?action=toggle_handled</strong>
                            <p class="mb-2">Toggle behandelde status</p>
                            <button class="copy-btn" onclick="copyToClipboard('PATCH /api/inquiries/1?action=toggle_handled')">Kopieer</button>
                        </div>

                        <div class="api-endpoint method-patch">
                            <span class="method-badge badge-patch">PATCH</span>
                            <strong>/api/inquiries/{id}?action=complete</strong>
                            <p class="mb-2">Markeer aanvraag als behandeld</p>
                            <button class="copy-btn" onclick="copyToClipboard('PATCH /api/inquiries/1?action=complete')">Kopieer</button>
                        </div>

                        <h5>POST/PUT Request Body:</h5>
                        <div class="code-block">
{
  "object": 1,
  "fullname": "Jan Janssen",
  "replyemail": "jan@example.com",
  "message": "Ik ben geïnteresseerd in dit object."
}
                        </div>

                        <h5>Response Voorbeeld:</h5>
                        <div class="code-block">
[
  {
    "id": 1,
    "objectid": 1,
    "fullname": "Jan Janssen",
    "replyemail": "jan@example.com",
    "message": "Ik ben geïnteresseerd in dit object.",
    "handled": 0
  }
]
                        </div>
                    </div>
                </div>

                <!-- Error Handling -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>⚠️ Error Handling</h3>
                    </div>
                    <div class="card-body">
                        <p>De API gebruikt standaard HTTP status codes:</p>
                        <ul>
                            <li><strong>200</strong> - Success</li>
                            <li><strong>201</strong> - Created (voor POST requests)</li>
                            <li><strong>400</strong> - Bad Request</li>
                            <li><strong>401</strong> - Unauthorized (authenticatie vereist)</li>
                            <li><strong>403</strong> - Forbidden (onvoldoende rechten)</li>
                            <li><strong>404</strong> - Not Found</li>
                            <li><strong>405</strong> - Method Not Allowed</li>
                            <li><strong>500</strong> - Internal Server Error</li>
                        </ul>
                        
                        <h5>Error Response Voorbeelden:</h5>
                        <div class="code-block">
# 400 Bad Request
{
  "error": "Invalid JSON input"
}

# 401 Unauthorized
{
  "error": "Authentication required"
}

# 403 Forbidden
{
  "error": "Insufficient permissions"
}

# 404 Not Found
{
  "error": "Resource not found"
}

# 405 Method Not Allowed
{
  "error": "Method not allowed"
}

# 500 Internal Server Error
{
  "error": "Internal server error: [details]"
}
                        </div>
                    </div>
                </div>

                <!-- Testing -->
                <div class="card mb-4 api-section">
                    <div class="card-header">
                        <h3>🧪 API Testing</h3>
                    </div>
                    <div class="card-body">
                        <p>Test de API endpoints met de volgende tools:</p>
                        <ul>
                            <li><strong>cURL:</strong> <code>curl http://localhost:8080/api/cities</code></li>
                            <li><strong>Postman:</strong> Import de endpoints voor interactieve testing</li>
                            <li><strong>Browser:</strong> Directe GET requests via URL</li>
                        </ul>
                        
                        <h5>cURL Voorbeelden:</h5>
                        <div class="code-block">
# ===== AUTHENTICATIE =====
# JSON Login
curl -X POST http://localhost:8080/api/auth \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'

# Huidige gebruiker ophalen (met Basic Auth)
curl -u admin:password http://localhost:8080/api/auth

# ===== PUBLIEKE ENDPOINTS (geen authenticatie) =====
# Alle steden
curl http://localhost:8080/api/cities

# Steden met objecten
curl http://localhost:8080/api/cities?used=true

# Alle objecten
curl http://localhost:8080/api/objects

# Objecten in Amsterdam
curl http://localhost:8080/api/objects?city=17

# Alle eigenschappen
curl http://localhost:8080/api/properties

# ===== BESCHERMDE ENDPOINTS (authenticatie vereist) =====
# Nieuwe stad aanmaken
curl -u admin:password -X POST http://localhost:8080/api/cities \
  -H "Content-Type: application/json" \
  -d '{"citiename":"Nieuwe Stad"}'

# Stad updaten
curl -u admin:password -X PUT http://localhost:8080/api/cities/17 \
  -H "Content-Type: application/json" \
  -d '{"citiename":"Amsterdam Updated"}'

# Stad verwijderen
curl -u admin:password -X DELETE http://localhost:8080/api/cities/17

# Nieuw object aanmaken
curl -u admin:password -X POST http://localhost:8080/api/objects \
  -H "Content-Type: application/json" \
  -d '{"title":"Nieuw Object","price":"500000","adress":"Straat 123","cityid":17}'

# Object updaten
curl -u admin:password -X PUT http://localhost:8080/api/objects/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Updated Object","price":"450000"}'

# Object verwijderen
curl -u admin:password -X DELETE http://localhost:8080/api/objects/1

# Nieuwe eigenschap aanmaken
curl -u admin:password -X POST http://localhost:8080/api/properties \
  -H "Content-Type: application/json" \
  -d '{"propertie":"Zwembad"}'

# Eigenschap updaten
curl -u admin:password -X PUT http://localhost:8080/api/properties/1 \
  -H "Content-Type: application/json" \
  -d '{"propertie":"Grote Tuin"}'

# Alle medewerkers
curl -u admin:password http://localhost:8080/api/staff

# Alleen admins
curl -u admin:password http://localhost:8080/api/staff?admins=true

# Nieuwe medewerker aanmaken
curl -u admin:password -X POST http://localhost:8080/api/staff \
  -H "Content-Type: application/json" \
  -d '{"username":"nieuwe_user","email":"user@vrijwonen.nl","password":"wachtwoord123","admin":0}'

# Medewerker updaten
curl -u admin:password -X PUT http://localhost:8080/api/staff/1 \
  -H "Content-Type: application/json" \
  -d '{"email":"nieuwe_email@vrijwonen.nl"}'

# Medewerker verwijderen
curl -u admin:password -X DELETE http://localhost:8080/api/staff/1

# Alle aanvragen
curl -u admin:password http://localhost:8080/api/inquiries

# Onbehandelde aanvragen
curl -u admin:password http://localhost:8080/api/inquiries?unhandled=true

# Aanvragen met object info
curl -u admin:password http://localhost:8080/api/inquiries?with_object_info=true

# Nieuwe aanvraag maken
curl -u admin:password -X POST http://localhost:8080/api/inquiries \
  -H "Content-Type: application/json" \
  -d '{"object":1,"fullname":"Jan Janssen","replyemail":"jan@example.com","message":"Interesse!"}'

# Aanvraag updaten
curl -u admin:password -X PUT http://localhost:8080/api/inquiries/1 \
  -H "Content-Type: application/json" \
  -d '{"message":"Bijgewerkt bericht"}'

# Aanvraag als behandeld markeren
curl -u admin:password -X PATCH http://localhost:8080/api/inquiries/1?action=complete

# Behandelde status toggle
curl -u admin:password -X PATCH http://localhost:8080/api/inquiries/1?action=toggle_handled
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show temporary feedback
                event.target.textContent = 'Gekopieerd!';
                setTimeout(function() {
                    event.target.textContent = 'Kopieer';
                }, 2000);
            });
        }
    </script>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("api_documentation.php");
?>
