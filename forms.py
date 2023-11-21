from flask_wtf import FlaskForm
from wtforms import BooleanField
from wtforms.validators import DataRequired
from wtforms import StringField, PasswordField

# Define a form for reporting issues
class ReportForm(FlaskForm):
    reported = BooleanField('Report as Suspicious')

# Define a form for user registration
class RegistrationForm(FlaskForm):
    username = StringField('Username', validators=[DataRequired()])
    password = PasswordField('Password', validators=[DataRequired()])

# Define a form for user login
class LoginForm(FlaskForm):
    username = StringField('Username', validators=[DataRequired()])
    password = PasswordField('Password', validators=[DataRequired()])
