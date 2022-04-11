# Comber

### Description:
Comber is a simple blog/social media web application, created for sharing uncensored information with your peers.

### Login credentials:
Both email and username can be used to log in.
#### User 1:
    email: lodewijkxiv@test.com
    username: LodewijkXIV
    password: secret123

#### User 2:
    email: wjb@test.com
    username: Willem-Jan-Boudewijn
    password: secret123

### Comber functionality:
Feel free to explore the application as desired. 
1. Login with provided credentials (Landing page is an overview of the 10 most recent posts on comber).
2. Display and manage user-owned posts, including editing and deletion.
3. Posting new messages (the little '+' icon in the middle).
4. Logout (localstorage is cleared).

#### Known issues:
- Upon logout from pages that require the user to be logged in the route to the home page doesn't work.
- Upon login a reload is required for the changes in the navbar to take effect.
- Deletion of a post always works, even if the post doesn't exist.
- The time stamp of a post (postedAt) is 2 hours behind. 

### Sources:
Initial project structure and endpoint reference from: <a href="https://github.com/ahrnuld/restapi-complete">Mark de Haan & Wim Wiltenburg - GitHub</a>