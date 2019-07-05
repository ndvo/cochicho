# A capstone project for the Cybersecurity Specialization

This website was developed as a capstone project for the Cybersecurity Specialization, 
from the University of Maryland, College Park, at Coursera

This application is not meant to be used in production. It is Public Domain, but there are absolutely no guarantees associated to it.

## Requirements

- Code and database must be available to opponents
- Must implement techniques to make security usable
- Must be able to create and recover accounts
- Must be able to create and send messages to peers
- Must be able to read sent messages
- Messages must be private to the users related to it

## High-level Concept

Users create account freely. User's passwords are used to create a key-value pair. Users private keys are encrypted using symmetric encryption with a secret derived from users passwords, then stored. User's public keys are stored in plain text.

When a user sends a message, it is encrypted with both his and the recipient's public keys,  then stored in the database. Both the "sent" and "received" pages are created using the current user's private key, decrypted with his/her password.

Recovering account necessarily implies that the user loses access to all messages. To recover the messages, the interlocutor needs to authorize the user to grab a copy. When authorization is given the message is decrypted with the interlocutor's private key (he must be logged in) and encrypted with the new public key of the recovering user.

## Technologies

### Programming languages

PHP was one of the languages students could choose. I chose it because I thought it would be easier to find peers to be part of the team. I ended up building the application by myself.

### Third-party libraries and frameworks

Whenever feasible third-party libraries and frameworks were avoided. 

The application is written with vanilla PHP, leveraging several functionalities provided by the language that would be typical of a framework, such as session handling and templating. As these features are part of the language they were used at will.

Preventing SQL injection was done using PHP PDO's parameter binding. Cross Site Scripting was prevented using HTML purifier. Building custom code for such tasks would be naive.

### Interface

Interface is built with HTML5, taking advantage of its client-side validation. Whenever possible relying on third-party libraries was avoided.
