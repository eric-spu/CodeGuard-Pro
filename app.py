from flask import Flask, render_template, request, redirect, url_for, flash
from flask_sqlalchemy import SQLAlchemy
from flask_wtf import FlaskForm
from flask_login import LoginManager, UserMixin, login_user, current_user, logout_user, login_required
from werkzeug.security import generate_password_hash, check_password_hash
from datetime import datetime, timedelta
import os

app = Flask(__name__)
app.config['SECRET_KEY'] = 'your-secret-key'
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///issues.db'
db = SQLAlchemy(app)
login_manager = LoginManager(app)
login_manager.login_view = 'login'

# Enable session protection with a strong secret key
app.config['SESSION_TYPE'] = 'filesystem'
app.config['SESSION_PERMANENT'] = False
app.config['SESSION_USE_SIGNER'] = True
app.config['PERMANENT_SESSION_LIFETIME'] = timedelta(minutes=30)

# Enforce HTTPS
if 'DYNO' in os.environ:  # Heroku deployment check
    from flask_sslify import SSLify
    sslify = SSLify(app)

# Define the User model for user authentication
class User(db.Model, UserMixin):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(50), unique=True, nullable=False)
    password_hash = db.Column(db.String(128))

    def set_password(self, password):
        self.password_hash = generate_password_hash(password)

    def check_password(self, password):
        return check_password_hash(self.password_hash, password)

# Define the Issue model for tracking issues
class Issue(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    description = db.Column(db.String(200), nullable=False)
    severity = db.Column(db.String(20), nullable=False)
    status = db.Column(db.String(20), default='Open')
    assignee = db.Column(db.String(50))
    language = db.Column(db.String(50), nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    closed_at = db.Column(db.DateTime)
    reported = db.Column(db.Boolean, default=False)
    removed = db.Column(db.Boolean, default=False)

# Define a form for reporting issues
class ReportForm(FlaskForm):
    reported = BooleanField('Report as Suspicious')

# Routes

# Display all issues
@app.route('/')
def index():
    issues = Issue.query.all()
    return render_template('index.html', issues=issues)

# Create a new issue
@app.route('/create', methods=['GET', 'POST'])
@login_required
def create_issue():
    if request.method == 'POST':
        description = request.form['description']
        severity = request.form['severity']
        language = request.form['language']
        assignee = request.form['assignee']

        new_issue = Issue(description=description, severity=severity, language=language, assignee=assignee)
        db.session.add(new_issue)
        db.session.commit()

        flash('Issue created successfully', 'success')
        return redirect(url_for('index'))

    return render_template('create_issue.html')

# Update an existing issue
@app.route('/update/<int:id>', methods=['GET', 'POST'])
@login_required
def update_issue(id):
    issue = Issue.query.get(id)

    if request.method == 'POST':
        issue.description = request.form['description']
        issue.severity = request.form['severity']
        issue.language = request.form['language']
        issue.assignee = request.form['assignee']

        db.session.commit()
        flash('Issue updated successfully', 'success')
        return redirect(url_for('index'))

    return render_template('update_issue.html', issue=issue)

# Close an issue
@app.route('/close/<int:id>')
@login_required
def close_issue(id):
    issue = Issue.query.get(id)
    issue.status = 'Closed'
    issue.closed_at = datetime.utcnow()

    db.session.commit()
    flash('Issue closed successfully', 'success')
    return redirect(url_for('index'))

# Report an issue
@app.route('/report/<int:id>', methods=['GET', 'POST'])
@login_required
def report_issue(id):
    issue = Issue.query.get(id)
    form = ReportForm()

    if form.validate_on_submit():
        issue.reported = form.reported.data
        db.session.commit()

        flash('Issue reported successfully', 'success')
        return redirect(url_for('index'))

    return render_template('report_issue.html', issue=issue, form=form)

# Remove an issue
@app.route('/remove/<int:id>')
@login_required
def remove_issue(id):
    issue = Issue.query.get(id)
    issue.removed = True

    db.session.commit()
    flash('Issue removed successfully', 'success')
    return redirect(url_for('index'))

# User Registration
@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']

        new_user = User(username=username)
        new_user.set_password(password)

        db.session.add(new_user)
        db.session.commit()

        flash('Account created successfully. You can now log in.', 'success')
        return redirect(url_for('login'))

    return render_template('register.html')

# User Login
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']

        user = User.query.filter_by(username=username).first()

        if user and user.check_password(password):
            login_user(user, remember=True)
            flash('Login successful', 'success')
            return redirect(url_for('index'))
        else:
            flash('Login failed. Check your username and password.', 'danger')

    return render_template('login.html')

# User Logout
@app.route('/logout')
@login_required
def logout():
    logout_user()
    flash('Logout successful', 'success')
    return redirect(url_for('index'))

if __name__ == '__main__':
    db.create_all()
    app.run(debug=True)
