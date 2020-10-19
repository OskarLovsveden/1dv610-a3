# Main document for assignment.

I have many flaws and am trying to hand it in as best as I can, it is what it is sadly.

I wanted to make the game I made for A3 have the ability to select a difficulty. This is a missing feature which I wanted to add and would have made testing much easier with userdecided ranges for the game.

All my time went into making my module(s).
Separating out the login system and also the session storage and the flash messages. I have few comments and probably too many string dependencies. But all in all I feel good about my modules.

I've also focused on trying to get my code reading like a newspaper with proper naming, as to not having to comment as much!

A3 and Login Module both include Settings/GameSettings files.
Remove .default from these files and fill in the information asked for by th edefault strings.

URL: https://ol222hf-assignment-3.000webhostapp.com/

# Use Cases A3

## UC1 Authenticate User

## Main scenario

1.  Starts when a user wants to authenticate.
2.  System asks for username, password, and if system should save the user credentials
3.  User provides username and password
4.  System authenticates the user and presents that authentication succeeded

## Alternate Scenarios

- 3a. User wants the system to keep user credentials for easier login.
  1. The system authenticates the user and presents that the authentication succeeded and that the user credentials was saved.
- 4a. User could not be authenticated
  1.  System presents an error message
  2.  Step 2 in main scenario

---

## UC2 View high score

## Main scenario

1.  Starts when a user wants to view the high score
2.  System shows a list of high scores

## Alternate Scenarios

- 2a. There where no high scores yet and the list is empty

---

## UC3 Save high score

## Preconditions

1. User has won a game
2. User is logged in

## Main scenario

1.  Starts when a user wants to save their high score
2.  The system presents the choice to save to high score or reset game
3.  User chooses to save to high score
4.  The system presents the high score list

## Alternate Scenarios

- 3a. User logs out
  1.  User can now only reset game
- 3b. User resets game
  1.  System starts a new round

---

## Test case 2.1, View high score with saved items

Normal navigation to page, page is shown.

### Input:

- Press high score in the upper navmenu.

### Output:

- The page is blank.

---

## Test case 2.2: View high score without saved items

Normal navigation to page, page is shown.

### Input:

- Press high score in the upper navmenu.

### Output:

- The page displays the high score list.

---

## Test case 3.1: Save to high score list

Make sure the win is saved to high score.

### Input:

- Press the "Save to highscore" button.

### Output:

- The page is redirected to the high score list.

---

## Test case 3.1: Save to high score list

Make sure you can't save a win when logged out.

### Input:

- Press the "logout" button.

### Output:

- The option(button) to "Save to highscore" disappears.

---

## Test case 3.1: Save to high score list

Make sure you can reset the game.

### Input:

- Press the "reset" button.

### Output:

- The page redirect to the game screen and the user can play a new round.
