import smtplib, ssl
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
import sys



port = 465  # For SSL
smtp_server = "smtp.gmail.com"
sender_email = "konodiodaaaaaaaaaaaaaaaaaaa@gmail.com"  # Enter your address
receiver_email = sys.argv[1]  # Enter receiver address
name = sys.argv[2]
token = sys.argv[3]
password = "9745emhome"

message = MIMEMultipart("alternative")
message["Subject"] = "Marcounter"
message["From"] = sender_email
message["To"] = receiver_email

# Create the plain-text and HTML version of your message
text = """\
Marcounter (Código de verificação)"""
html = """\
<html>
  <head>
      <h1>Marcounter (Código de verificação)</h1>
  </head>
  <body>
    <p>Ola """+ name +""", aqui segue o token para a ativação da sua conta: 
    <h3>"""+token+"""</h3>
    </p>
  </body>
</html>
"""

part1 = MIMEText(text, "plain")
part2 = MIMEText(html, "html")

message.attach(part1)
message.attach(part2)

context = ssl.create_default_context()
with smtplib.SMTP_SSL(smtp_server, port, context=context) as server:
    server.login(sender_email, password)
    server.sendmail(sender_email, receiver_email, message.as_string())
print('email sent')
