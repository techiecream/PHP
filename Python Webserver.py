import BaseHTTPServer
import socket
import random
import sqlite3

HOST = '192.168.43.52'
MIN_PORT = 30000
MAX_PORT = 40000
DATABASE = 'users.db'


class LoginHandler(BaseHTTPServer.BaseHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        self.username = ''  # Initialize username attribute
        BaseHTTPServer.BaseHTTPRequestHandler.__init__(self, *args, **kwargs)

    def do_GET(self):
        if self.path == '/':
            self.send_response(200)
            self.send_header('Content-type', 'text/html')
            self.end_headers()

            # Serve the login form with CSS styling
            login_form = '''
            <html>
            <head>
<style>
                    body {{
                        font-family: Arial, sans-serif;
                        padding: 20px;
                    }}

                    h1 {{
                        color: #333;
                    }}

                    .form-group {{
                        margin-bottom: 10px;
                    }}

                    .form-group label {{
                        display: block;
                        font-weight: bold;
                    }}

                    .form-group input {{
                        width: 100%;
                        padding: 5px;
                    }}

                    .form-group button {{
                        padding: 5px 10px;
                        background-color: #333;
                        color: #fff;
                        border: none;
                        cursor: pointer;
                    }}
                </style>
            </head>
            <body>
            <h1>Login</h1>
            <form method="post" action="/login">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <input type="submit" value="Login">
            </form>
            <ul>
                <li><a href="/signup">Sign up</a></li>
            </ul>
            </body>
            </html>
            '''
            self.wfile.write(login_form)

        elif self.path == '/home':
            self.send_response(200)
            self.send_header('Content-type', 'text/html')
            self.end_headers()

            # Serve the home page with links and file upload form
            home_page = '''
            <html>
            <head>
<style>
                    body {{
                        font-family: Arial, sans-serif;
                        padding: 20px;
                    }}

                    h1 {{
                        color: #333;
                    }}

                    .form-group {{
                        margin-bottom: 10px;
                    }}

                    .form-group label {{
                        display: block;
                        font-weight: bold;
                    }}

                    .form-group input {{
                        width: 100%;
                        padding: 5px;
                    }}

                    .form-group button {{
                        padding: 5px 10px;
                        background-color: #333;
                        color: #fff;
                        border: none;
                        cursor: pointer;
                    }}
                </style>
            </head>
            <body>
            <h1>Welcome, {username}!</h1>
            <p>Here are some links:</p>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
            <form method="post" action="/upload" enctype="multipart/form-data">
                <label for="file">Upload File:</label>
                <input type="file" id="file" name="file">
                <input type="submit" value="Upload">
            </form>
            </body>
            </html>
            '''.format(username=self.username)
            self.wfile.write(home_page.encode('utf-8'))

        elif self.path == '/signup':
            self.send_response(200)
            self.send_header('Content-type', 'text/html')
            self.end_headers()

            # Serve the sign-up form with CSS styling
            signup_form = '''
            <html>
            <head>
<style>
                    body {{
                        font-family: Arial, sans-serif;
                        padding: 20px;
                    }}

                    h1 {{
                        color: #333;
                    }}

                    .form-group {{
                        margin-bottom: 10px;
                    }}

                    .form-group label {{
                        display: block;
                        font-weight: bold;
                    }}

                    .form-group input {{
                        width: 100%;
                        padding: 5px;
                    }}

                    .form-group button {{
                        padding: 5px 10px;
                        background-color: #333;
                        color: #fff;
                        border: none;
                        cursor: pointer;
                    }}
                </style>
            </head>
            <body>
            <h1>Sign Up</h1>
            <form method="post" action="/register">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <input type="submit" value="Sign Up">
            </form>
            </body>
            </html>
            '''
            self.wfile.write(signup_form)

        elif self.path == '/logout':
            # Clear the username attribute to log out the user
            self.username = ''
            self.send_response(302)
            self.send_header('Location', '/')
            self.end_headers()

        else:
            self.send_error(404)

    def do_POST(self):
        if self.path == '/login':
            # Read the form data
            content_length = int(self.headers['Content-Length'])
            post_data = self.rfile.read(content_length).decode('utf-8')
            username = ''
            password = ''

            # Extract username and password from form data
            for item in post_data.split('&'):
                key, value = item.split('=')
                if key == 'username':
                    username = value
                elif key == 'password':
                    password = value

            # Validate username and password against the database
            if validate_credentials(username, password):
                self.username = username
                self.send_response(302)
                self.send_header('Location', '/home')
                self.end_headers()
            else:
                self.send_response(401)
                self.send_header('Content-type', 'text/html')
                self.end_headers()

                # Serve the error page with CSS styling
                error_page = '''
                <html>
                <head>
<style>
                    body {{
                        font-family: Arial, sans-serif;
                        padding: 20px;
                    }}

                    h1 {{
                        color: #333;
                    }}

                    .form-group {{
                        margin-bottom: 10px;
                    }}

                    .form-group label {{
                        display: block;
                        font-weight: bold;
                    }}

                    .form-group input {{
                        width: 100%;
                        padding: 5px;
                    }}

                    .form-group button {{
                        padding: 5px 10px;
                        background-color: #333;
                        color: #fff;
                        border: none;
                        cursor: pointer;
                    }}
                </style>
                </head>
                <body>
                <h1>Login Failed</h1>
                <p>Invalid username or password.</p>
                </body>
                </html>
                '''
                self.wfile.write(error_page.encode('utf-8'))

        elif self.path == '/register':
            # Read the form data
            content_length = int(self.headers['Content-Length'])
            post_data = self.rfile.read(content_length).decode('utf-8')
            username = ''
            password = ''

            # Extract username and password from form data
            for item in post_data.split('&'):
                key, value = item.split('=')
                if key == 'username':
                    username = value
                elif key == 'password':
                    password = value

            # Check if the username is already taken
            if username_exists(username):
                self.send_response(409)
                self.send_header('Content-type', 'text/html')
                self.end_headers()

                # Serve the error page with CSS styling
                error_page = '''
                <html>
                <head>
<style>
                    body {{
                        font-family: Arial, sans-serif;
                        padding: 20px;
                    }}

                    h1 {{
                        color: #333;
                    }}

                    .form-group {{
                        margin-bottom: 10px;
                    }}

                    .form-group label {{
                        display: block;
                        font-weight: bold;
                    }}

                    .form-group input {{
                        width: 100%;
                        padding: 5px;
                    }}

                    .form-group button {{
                        padding: 5px 10px;
                        background-color: #333;
                        color: #fff;
                        border: none;
                        cursor: pointer;
                    }}
                </style>
                </head>
                <body>
                <h1>Sign Up Failed</h1>
                <p>Username already taken. Please choose a different username.</p>
                </body>
                </html>
                '''
                self.wfile.write(error_page.encode('utf-8'))
            else:
                # Create a new user in the database
                create_user(username, password)

                self.send_response(302)
                self.send_header('Location', '/')
                self.end_headers()

        elif self.path == '/upload':
            # Check if 'Content-Disposition' header is present
            if 'Content-Disposition' in self.headers:
                file_field = self.headers['Content-Disposition']
                if 'filename=' in file_field:
                    filename = file_field.split('filename=')[1]
                    with open(filename, 'wb') as file:
                        file.write(self.rfile.read())

                    self.send_response(200)
                    self.send_header('Content-type', 'text/html')
                    self.end_headers()

                    message = '<h1>File uploaded successfully!</h1>'
                    self.wfile.write(message.encode('utf-8'))
            else:
                self.send_error(400, 'Bad Request: File upload missing')
        else:
            self.send_error(404)


# Find an available port
def find_available_port():
    while True:
        port = random.randint(MIN_PORT, MAX_PORT)
        try:
            sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            sock.bind((HOST, port))
            sock.close()
            return port
        except socket.error:
            continue


def validate_credentials(username, password):
    # Connect to the SQLite database
    conn = sqlite3.connect(DATABASE)
    cursor = conn.cursor()

    # Execute the query to check if the username and password are valid
    cursor.execute("SELECT * FROM users WHERE username = ? AND password = ?", (username, password))
    result = cursor.fetchone()

    # Close the database connection
    conn.close()

    # Return True if the query result is not None (i.e., username and password match)
    return result is not None


def username_exists(username):
    # Connect to the SQLite database
    conn = sqlite3.connect(DATABASE)
    cursor = conn.cursor()

    # Execute the query to check if the username already exists
    cursor.execute("SELECT * FROM users WHERE username = ?", (username,))
    result = cursor.fetchone()

    # Close the database connection
    conn.close()

    # Return True if the query result is not None (i.e., username already exists)
    return result is not None


def create_user(username, password):
    # Connect to the SQLite database
    conn = sqlite3.connect(DATABASE)
    cursor = conn.cursor()

    # Execute the query to insert a new user
    cursor.execute("INSERT INTO users (username, password) VALUES (?, ?)", (username, password))

    # Commit the transaction and close the database connection
    conn.commit()
    conn.close()


if __name__ == '__main__':
    PORT = find_available_port()
    server_address = (HOST, PORT)

    # Create the HTTP server
    httpd = BaseHTTPServer.HTTPServer(server_address, LoginHandler)

    print('Starting server on {}:{}'.format(HOST, PORT))
    httpd.serve_forever()
