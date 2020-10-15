# UC1 Input guess
Preconditions
A user is authenticated. Ex. UC1, UC3

Main scenario
Starts when a user no longer wants to be logged in
The system present a logout choice
User tells the system he wants to log out.
The system logs the user out and presents a feedback message

# UC2 View Highscore
Precondition
UC1. 3a User wants the system to keep user credentials for easier login.

Main scenario
Starts when a user wants to authenticate with saved credentials
System authenticated the user and presents that the authentication succeeded and that it happened with saved credentials.
Alternate Scenarios
2a. The user could not be authenticated (to old credentials > 30 days) (Wrong credentials), Manipulated credentials
System presents error message
Step 2 in UC1.