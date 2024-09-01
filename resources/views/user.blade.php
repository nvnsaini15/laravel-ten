<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        .error_show {
            color:red;
        }
        #user_table {
            margin: auto;
        }
    </style>
    <script src="{{ asset('js/axios.min.js') }}"></script>
</head>
<body>
    <div id="content" style="text-align: center;">
        <h1>Create User</h1>

        <label for="name">Name:</label>
        <input type="text" id="name" >
        <span id="name_error" class="error_show"></span>
        <br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" >
        <span id="email_error" class="error_show"></span>
        <br><br>
        
        <label for="phone">Phone:</label>
        <input type="text" id="phone" >
        <span id="phone_error" class="error_show"></span>
        <br><br>

        <label for="role">Role:</label>
        <select id="role">
            <option value="0" >Select</option>
        </select>
        <span id="role_id_error" class="error_show"></span>
        <br><br>

        <label for="description">Description:</label>
        <textarea id="description" rows="4" cols="50" ></textarea>
        <span id="description_error" class="error_show"></span>
        <br><br>
        
        <label for="profile_image_file">Profile Image:</label>
        <input type="file" id="profile_image_file"  accept="image/*" >
        <span id="profile_image_file_error" class="error_show"></span>
        <br><br>
        
        <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
        <button onclick="form_submit()">Submit</button>
        <br><br>

        <table id="user_table" border="1px">

        </table>

    </div>

    <script>
        
        (function() {
            axios.get('/api/roles')
            .then(function (response) {
                response.data.forEach(function (item) {
                    document.getElementById('role').add(new Option(item.role, item.id));
                });
            })
            .catch(function (error) {
                console.error('error : ', error);
            });
        })();

        function form_submit() {

            const collection = document.getElementsByClassName("error_show");
            for (const [key, value] of Object.entries(collection)) {
                document.getElementById(value.id).innerHTML = '';
            }

            var isValid = true;

            var name = document.getElementById('name').value;
            if (name.trim() === '') {
                alert('Please enter your name.');
                isValid = false;
            }

            var email = document.getElementById('email').value;
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                isValid = false;
            }

            var phone = document.getElementById('phone').value;
            var phonePattern = /^[0-9]{10}$/; // Example pattern for 10-digit numbers
            if (!phonePattern.test(phone)) {
                alert('Please enter a valid 10-digit phone number.');
                isValid = false;
            }

            var description = document.getElementById('description').value;
            if (description.trim() === '') {
                alert('Please enter a description.');
                isValid = false;
            }

            var role = document.getElementById('role').value;
            if (role === '0') {
                alert('Please select a role.');
                isValid = false;
            }

            var profileImage = document.getElementById('profile_image_file').files[0];
            if (!profileImage) {
                alert('Please upload a profile image.');
                isValid = false;
            } else if (!['image/jpeg', 'image/png', 'image/gif'].includes(profileImage.type)) {
                alert('Please upload a valid image file (JPEG, PNG, GIF).');
                isValid = false;
            }

            if (isValid) {
                var formData = new FormData();
                formData.append('_token', document.getElementById('csrf_token').value);
                formData.append('name', document.getElementById('name').value);
                formData.append('email', document.getElementById('email').value);
                formData.append('phone', document.getElementById('phone').value);
                formData.append('role_id', document.getElementById('role').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('profile_image_file', document.getElementById('profile_image_file').files[0]);

                axios.post('/api/users', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function (response) {
                    if (response.data[1] == 422) {                    

                        for (const [key, value] of Object.entries(response.data[0])) {
                            var field = `${key}_error`;
                            document.getElementById(field).innerHTML = `${value}`;
                        }
                    } else {
                        var allRows = '<tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Description</th><th>ProfileImage</th></tr>';
                        // show all data on same page
                        response.data[0].forEach(function (item) {
                            allRows += `<tr><td>${item.name}</td><td>${item.email}</td><td>${item.phone}</td><td>${item.role.role}</td><td>${item.description}</td><td><img src="${item.profile_image}" width="60" height="60"></td></tr>`;  
                        });
                        document.getElementById('user_table').innerHTML = allRows;
                        alert('User created successfully');
                    }
                    
                })
                .catch(function (error) {
                    console.error('error :', error);
                });
            }

        }

            
    </script>
</body>
</html>
