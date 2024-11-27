

---

# **Bazarin PHP Library**  
**A lightweight and secure PHP query builder using MySQLi.**

---

## **Features**  
- Simplifies database operations with a clean API.  
- Prevents SQL injection using prepared statements.  
- Supports common CRUD operations: `select`, `insert`, `update`, `delete`.  

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

---

## **System Requirements**  
- PHP 7.4 or higher  
- MySQL 5.7 or higher  

---

## **Contributing**  
Feel free to contribute by submitting pull requests.  

---

## **License**  
This library is open-source and licensed under the MIT License.  

---

