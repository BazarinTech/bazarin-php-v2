

---

# **Bazarin PHP Library**  
**A lightweight and secure PHP library.**

---

## **Features**  
- Simplifies database operations with a clean API.  
- Prevents SQL injection using prepared statements.  
- Supports common CRUD operations: `select`, `insert`, `update`, `delete`.
-  Dates and time management according to regions.
-  Encryption and decryptions.
-  File management and uploads.
-  APIS simplifications.   

---

## **Getting Started**  

### **1. Database Connection**  
Establish a connection using the `Connection` class:  
```php
include 'Connection.php';

$connection = new Connection('localhost', 'username', 'password', 'database_name');
$conn = $connection->connect();
```

---

### **2. Query Builder Initialization**  
Initialize the `QueryBuilder` with the database connection:  
```php
include 'QueryBuilder.php';

$query = new QueryBuilder($conn);
```

---

## **Usage**  

### **1. `select()`**  
Retrieve data from a table.  
```php
$results = $query->select('users', '*', ['id' => 1]);
```

---

### **2. `insert()`**  
Add new data to a table.  
```php
$newId = $query->insert('users', ['name' => 'Jane Doe', 'email' => 'jane@example.com']);
```

---

### **3. `update()`**  
Modify existing records.  
```php
$affectedRows = $query->update('users', ['email' => 'new@example.com'], ['id' => 1]);
```

---

### **4. `delete()`**  
Remove records from a table.  
```php
$deletedRows = $query->delete('users', ['id' => 1]);
```

---

## **Error Handling**  
Use a `try-catch` block to handle exceptions:  
```php
try {
    $query->select('non_existing_table');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

Here's what you should add to the README file to document the `RestClient` and `ApiManager` classes:

---

# API Library Documentation

This library provides a simple way to interact with RESTful APIs using PHP's cURL. It supports all standard HTTP methods (GET, POST, PUT, DELETE) and includes features such as custom headers, error handling, and debug logging.

## Features
- Perform CRUD operations with RESTful APIs.
- Supports custom headers for authorization or other requirements.
- Handles HTTP errors and invalid JSON responses gracefully.
- Debugging mode for logging requests and responses.
- Easy-to-use methods for common API operations.

---

## Installation
Simply download or clone this repository. Include the `RestClient` and `ApiManager` classes in your project.

```php
require_once './APIS/curl.php';

```

---

## Usage

### Initialization
```php
$apiManager = new ApiManager(['Authorization: Bearer your_api_token'], true);
```
- The first parameter is an array of default headers.
- The second parameter enables or disables debug mode.

---

### Methods

#### Fetch All Records
Fetches data using a GET request.
```php
$response = $apiManager->fetchAll('https://api.example.com/items');
```

#### Create a Record
Sends a POST request to create a new resource.
```php
$newItem = $apiManager->create('https://api.example.com/items', [
    'name' => 'Item Name',
    'description' => 'Description of the item'
]);
```

#### Update a Record
Sends a PUT request to update an existing resource.
```php
$updateResponse = $apiManager->update('https://api.example.com/items/1', [
    'name' => 'Updated Name'
]);
```

#### Delete a Record
Sends a DELETE request to remove a resource.
```php
$deleteResponse = $apiManager->delete('https://api.example.com/items/1');
```

---

### Advanced Features

#### Custom Headers
You can pass additional headers for specific requests:
```php
$response = $apiManager->fetchAll('https://api.example.com/items', ['Custom-Header: value']);
```

#### Debugging Mode
Enable debug mode to log detailed information about each request and response:
```php
$apiManager->enableDebugMode(true);
```

#### Error Handling
The library automatically throws exceptions for:
- HTTP errors (status codes 400 and above).
- cURL connection errors.
- Invalid JSON responses.

Use a try-catch block to handle exceptions:
```php
try {
    $response = $apiManager->fetchAll('https://api.example.com/items');
} catch (RuntimeException $e) {
    echo "Error: " . $e->getMessage();
}
```

---

### Example
```php
$apiManager = new ApiManager(['Authorization: Bearer your_api_token'], true);

try {
    // Fetch items
    $items = $apiManager->fetchAll('https://api.example.com/items');
    print_r($items);

    // Create an item
    $newItem = $apiManager->create('https://api.example.com/items', ['name' => 'New Item']);
    echo "Created Item ID: " . $newItem['id'];

    // Update an item
    $updatedItem = $apiManager->update('https://api.example.com/items/1', ['name' => 'Updated Name']);
    echo "Updated Item: " . json_encode($updatedItem);

    // Delete an item
    $apiManager->delete('https://api.example.com/items/1');
    echo "Item deleted successfully.";
} catch (RuntimeException $e) {
    echo "API Error: " . $e->getMessage();
}
```
The provided `FileGetContent` class is designed to handle incoming HTTP requests with JSON payloads and apply CORS (Cross-Origin Resource Sharing) headers to allow secure access from specified origins. Below is an explanation and usage guide for the class, which you can also include in your README file.

---

# FileGetContent Class Documentation

## Features
- Enables CORS support for handling cross-origin requests.
- Processes incoming JSON payloads from HTTP requests.
- Handles `OPTIONS` preflight requests automatically for better CORS compliance.

---

## Methods

### Constructor: `__construct($origin)`
Initializes the class with the allowed origin for CORS requests.
- **Parameter**: 
  - `$origin` (string): The domain allowed to access this API. For example, `'https://example.com'`.

---

### `cors_auth()`
Handles CORS headers to allow secure communication between origins.
- Adds the following headers:
  - `Access-Control-Allow-Origin`: Matches the provided origin.
  - `Access-Control-Allow-Methods`: Specifies allowed HTTP methods (POST, GET, OPTIONS).
  - `Access-Control-Allow-Headers`: Lists allowed headers (e.g., Content-Type, Authorization).
  - `Access-Control-Allow-Credentials`: Indicates whether cookies and credentials are allowed.

- Automatically responds to `OPTIONS` requests with a 200 status code and exits. This is necessary for preflight requests in modern browsers.

---

### `get_content()`
Processes the request body and decodes JSON payloads.
- **Returns**: 
  - Associative array: Parsed JSON from the request body.
  - If no JSON is sent or it is invalid, `null` is returned.

- **Example**:
  ```php
  $fileGetContent = new FileGetContent('https://example.com');
  $data = $fileGetContent->get_content();
  print_r($data); // Output the parsed JSON payload
  ```

---

## Example Usage
Here is an example of how to use the `FileGetContent` class in an API endpoint:

```php
// Include the class file
require_once 'FileGetContent.php';

// Set the allowed origin
$fileHandler = new FileGetContent('https://your-frontend-domain.com');

// Retrieve the JSON content from the request
$data = $fileHandler->get_content();

// Process the incoming data
if ($data) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Data received successfully',
        'data' => $data,
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid or missing JSON payload',
    ]);
}
```

---

## Notes
1. **CORS Configuration**: Update the `$origin` parameter in the constructor to match your frontend domain. Use `'*'` to allow all origins (not recommended for production).
2. **Security**: This class does not validate or sanitize the incoming JSON payload. Ensure that additional validation is performed as needed.

---
Great! The provided code uses AES-256-CBC encryption, which is a symmetric encryption method. It generates an encryption key, uses an initialization vector (IV), and supports both encryption and decryption processes. Below is the updated documentation for the `Crypt` class using your provided code.

---

# Crypt Class Documentation

## Overview

The `Crypt` class provides functionality for **encrypting** and **decrypting** data using the AES-256-CBC encryption method. The class uses a symmetric encryption system where the same key is used for both encryption and decryption. The key must be securely managed, as anyone with access to the key can decrypt the data.

## Methods

- **`__construct($key)`**: Initializes the class with an encryption key.
- **`encrypt($data)`**: Encrypts the provided data.
- **`decrypt($data)`**: Decrypts the provided encrypted data.

---

### 1. **`__construct($key)`**

#### Description:
The constructor method initializes the encryption key used for both encryption and decryption.

#### Parameters:
- **`$key`** (string): The encryption key used for AES-256-CBC. It is crucial that this key is kept secret and secure.

#### Throws:
- **`InvalidArgumentException`**: If the provided key is empty.

#### Example Usage:
```php
$crypt = new Crypt("my_secret_key");  // Initializes with the encryption key
```

---

### 2. **`encrypt($data)`**

#### Description:
Encrypts the provided plaintext data using AES-256-CBC encryption. The method generates a random initialization vector (IV) for encryption to enhance security.

#### Parameters:
- **`$data`** (string): The plaintext data to encrypt.

#### Returns:
- (string): The encrypted data as a base64-encoded string, containing both the encrypted data and the IV.

#### Example Usage:
```php
$crypt = new Crypt("my_secret_key");
$plaintext = "Hello, this is a secret message!";
$encryptedData = $crypt->encrypt($plaintext);
echo $encryptedData;  // Encrypted data in base64 format
```

---

### 3. **`decrypt($data)`**

#### Description:
Decrypts the provided encrypted data using the AES-256-CBC method and the same encryption key. The method extracts the IV from the base64-encoded string and uses it for decryption.

#### Parameters:
- **`$data`** (string): The base64-encoded encrypted data containing the ciphertext and IV.

#### Returns:
- (string): The decrypted plaintext data.

#### Example Usage:
```php
$crypt = new Crypt("my_secret_key");
$encryptedData = "base64_encrypted_data_with_iv";  // The base64-encoded encrypted string
$decryptedData = $crypt->decrypt($encryptedData);
echo $decryptedData;  // Decrypted message
```

---

## Important Notes

1. **Key Management**: The encryption key is critical for both encrypting and decrypting data. Ensure the key is kept secure and not exposed.

2. **AES-256-CBC**: This encryption method is considered very secure. It uses a 256-bit key and a 128-bit initialization vector (IV). The IV is randomly generated for each encryption operation to prevent identical data from having the same ciphertext.

3. **Initialization Vector (IV)**: An IV is required for AES-CBC mode. It is generated randomly for each encryption to ensure that the same plaintext encrypted multiple times produces different ciphertexts. The IV is stored alongside the ciphertext in the base64-encoded string.

4. **Base64 Encoding**: The encrypted data and IV are returned as a base64-encoded string. This is done so that binary data can be safely transmitted over protocols that only support text-based data.

---

## Example Workflow

### Encryption
1. **Encrypt data** using the `encrypt()` method:
    ```php
    $crypt = new Crypt("my_secret_key");
    $plaintext = "Sensitive information that needs encryption.";
    $encrypted = $crypt->encrypt($plaintext);
    echo $encrypted;
    ```

### Decryption
2. **Decrypt data** using the `decrypt()` method:
    ```php
    $crypt = new Crypt("my_secret_key");
    $encryptedData = "base64_encrypted_string";
    $decrypted = $crypt->decrypt($encryptedData);
    echo $decrypted;  // Should display the original plaintext
    ```

---

## Conclusion

The `Crypt` class provides an easy and secure way to encrypt and decrypt data using AES-256-CBC. The class ensures confidentiality by encrypting data with a secure algorithm and managing the initialization vector. To decrypt the data, the same key used for encryption is required.

---
Here is the documentation for the `FileHelper` class:

---

# **FileHelper Class Documentation**

The `FileHelper` class provides utility functions for handling file uploads in PHP. It allows for uploading files to a specified destination with optional validation for file type and file size.

## **Methods**

### `upload($file, $destination, $allowedTypes = [], $maxSize = 0)`

This method is used to upload a file to the server. It optionally validates the file's type and size before moving it to the specified destination.

#### **Parameters:**

- **`$file`** (array) – The file to be uploaded, typically from the `$_FILES` superglobal array (e.g., `$_FILES['file']`).
  
- **`$destination`** (string) – The target location on the server where the file will be uploaded (e.g., `'uploads/filename.ext'`).
  
- **`$allowedTypes`** (array, optional) – An array of allowed MIME types for the file. This is an optional parameter. If provided, the function will check that the file type matches one of the allowed MIME types. Example: `['image/jpeg', 'image/png']`.
  
- **`$maxSize`** (int, optional) – The maximum file size allowed in bytes. If set to a value greater than 0, the file size will be checked before upload. Example: `2000000` (2MB).

#### **Return Value:**

- **`string`** – Returns the destination path of the uploaded file on success (e.g., `'uploads/filename.ext'`).
- **Throws Exception** – If the file fails the type or size validation, or if the upload fails, an exception is thrown with a relevant error message.

#### **Exceptions:**

The method may throw the following exceptions:
- **File upload error**: If the file upload fails due to an internal error (e.g., `$_FILES['file']['error']`).
- **Invalid file type**: If the uploaded file's MIME type does not match the allowed types.
- **File too large**: If the uploaded file exceeds the maximum allowed size.

---

## **Example Usage:**

```php
try {
    // File to be uploaded
    $file = $_FILES['file'];

    // Destination path
    $destination = 'uploads/' . basename($file['name']);

    // Optional validation for file type and size
    $allowedTypes = ['image/jpeg', 'image/png'];
    $maxSize = 2000000; // 2MB

    // Attempt to upload the file
    $uploadedFile = FileHelper::upload($file, $destination, $allowedTypes, $maxSize);
    echo "File uploaded successfully: $uploadedFile";
} catch (\Exception $e) {
    // Handle any errors that occur during the upload
    echo "Error: " . $e->getMessage();
}
```

In this example:
- The file is uploaded with type and size validation.
- If the file is of the wrong type or too large, an exception is thrown with an error message.
- On successful upload, the file path is returned.

---

## **Usage Notes:**

- If no validation is required, both `$allowedTypes` and `$maxSize` can be left empty or set to default values (empty array and 0, respectively).
  
- The MIME type of the uploaded file is determined using PHP's `mime_content_type()` function.

- The file is moved using PHP's `move_uploaded_file()` function, and the destination is returned on success.

- Exception handling should be implemented to catch errors related to file upload failures, invalid file types, or exceeding the file size limit.

---

### **Common Errors:**

- **File upload failed:** This could be due to an invalid file, server misconfiguration, or permission issues.
- **Invalid file type:** The uploaded file type does not match any of the allowed MIME types.
- **File too large:** The uploaded file exceeds the maximum allowed size.

---
Here is the documentation for the `DateHelper` class:

---

# **DateHelper Class Documentation**

The `DateHelper` class provides utility functions for working with dates in PHP. It includes a method to format a date according to a specified format.

## **Methods**

### `format($date, $format = 'Y-m-d')`

This method is used to format a given date into a specific format.

#### **Parameters:**

- **`$date`** (string|DateTime) – The date to be formatted. This can either be a string representing a date (e.g., `'2024-11-29'`) or a `DateTime` object.
  
- **`$format`** (string, optional) – The format in which the date should be returned. This is optional and defaults to `'Y-m-d'` (e.g., `2024-11-29`). You can use any format supported by PHP's `DateTime::format` method. For example, `'d/m/Y'` for `29/11/2024`, or `'l, F j, Y'` for `'Friday, November 29, 2024'`.

#### **Return Value:**

- **`string`** – Returns the formatted date as a string.

#### **Exceptions:**

- **InvalidArgumentException** – If the provided `$date` cannot be parsed into a valid date, an exception will be thrown.

---

## **Example Usage:**

```php
try {
    // Format a date string to the default format (Y-m-d)
    $formattedDate = DateHelper::format('2024-11-29');
    echo $formattedDate; // Output: 2024-11-29

    // Format a date string with a custom format (d/m/Y)
    $formattedDate = DateHelper::format('2024-11-29', 'd/m/Y');
    echo $formattedDate; // Output: 29/11/2024

    // Format a DateTime object with a custom format (l, F j, Y)
    $date = new \DateTime('2024-11-29');
    $formattedDate = DateHelper::format($date, 'l, F j, Y');
    echo $formattedDate; // Output: Friday, November 29, 2024
} catch (\Exception $e) {
    // Handle any errors that occur during the date formatting
    echo "Error: " . $e->getMessage();
}
```

In this example:
- A date string is formatted using the default format (`Y-m-d`).
- A custom format (`d/m/Y`) is used to format the date.
- A `DateTime` object is passed to the `format` method and is formatted as well.

---

## **Usage Notes:**

- The `$date` parameter can be a string (e.g., `'2024-11-29'`) or an instance of `DateTime`. If a string is passed, it will be converted into a `DateTime` object internally.
  
- The `$format` parameter is optional. If not provided, the default format (`'Y-m-d'`) will be used. The format string can follow PHP’s `DateTime::format` syntax.

- If the provided `$date` string is not a valid date, the method will throw an exception.

---

### **Common Errors:**

- **Invalid date format**: If the provided date string is invalid, it will cause a parsing error when creating a `DateTime` object.

## Requirements
- PHP 7.4 or higher.
- cURL extension enabled.

---

## License
This project is licensed under the MIT License. See the LICENSE file for details.



