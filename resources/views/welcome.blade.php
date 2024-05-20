<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IndexedDB Form Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <!-- sachintha dharmawardena-->
    <h1>Form with IndexedDB</h1>
    <div class="row">
    <div class="col-md-6">
    <form id="dataForm" >
        <label for="name">Customer Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="text" required><br><br>
        <button type="submit">Submit</button>
    </form>
    </div>
    <div class="col-md-6">
    <h2>Stored Data</h2>
    <table id="dataTable" class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
    </div>

<script>
     document.addEventListener('DOMContentLoaded', () => {
        let db;
        const request = indexedDB.open('FormDB', 1);

        request.onerror = function(event) {
                console.error('Database error:', event.target.errorCode);
            };


        request.onsuccess = function(event) {
                db = event.target.result;
                displayData();
            };

        request.onupgradeneeded = function(event) {
            db = event.target.result;
            const objectStore = db.createObjectStore('formData', { keyPath: 'id', autoIncrement: true });
            objectStore.createIndex('name', 'name', { unique: false });
            objectStore.createIndex('email', 'email', { unique: false });
            objectStore.createIndex('contact', 'contact', { unique: false });
        };

            const form = document.getElementById('dataForm');
                form.addEventListener('submit', event => {
                    event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const contact = document.getElementById('contact').value;

            const transaction = db.transaction(['formData'], 'readwrite');
            const objectStore = transaction.objectStore('formData');
            const request = objectStore.add({ name, email, contact });

            request.onsuccess = function() {
                displayData();
            };

            request.onerror = function(event) {
                    console.error('Error inserting data:', event.target.errorCode);
                };

            form.reset();
        });

        function displayData() {
                const transaction = db.transaction(['formData'], 'readonly');
                const objectStore = transaction.objectStore('formData');
                const request = objectStore.getAll();

                request.onsuccess = function(event) {
                    const data = event.target.result;
                    const tbody = document.querySelector('#dataTable tbody');
                    tbody.innerHTML = '';

                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td>${item.name}</td><td>${item.email}</td><td>${item.contact}</td>`;
                        tbody.appendChild(row);
                    });
                };

                request.onerror = function(event) {
                    console.error('Error fetching data:', event.target.errorCode);
                };

            }

     });
</script>

</body>
</html>
