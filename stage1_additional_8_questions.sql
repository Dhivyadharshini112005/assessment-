-- Run this once inside the existing ftaxi_qa database.
-- The original demo database already has 2 Stage 1 questions.
-- This adds 8 more so the employee test has exactly 10 active questions.

INSERT INTO stage1_questions (question, option_a, option_b, option_c, option_d, correct_option, active) VALUES
('How should you confirm the customer’s pickup location?', 'Repeat the pickup location and confirm it', 'Ignore the location', 'Ask for payment first', 'End the call', 'A', 1),
('What is the best tone for a telecaller speaking with a customer?', 'Polite and professional', 'Angry and loud', 'Casual and careless', 'Silent', 'A', 1),
('What should you do if you do not understand the customer?', 'Politely ask them to repeat or clarify', 'Guess the information', 'Disconnect immediately', 'Argue with them', 'A', 1),
('Before confirming a taxi booking, what should you verify?', 'Important booking details such as pickup and destination', 'Only the customer’s age', 'Only the vehicle color', 'Nothing', 'A', 1),
('How should confidential customer information be handled?', 'Keep it private and share it only when authorized', 'Share it with anyone', 'Post it online', 'Send it to other customers', 'A', 1),
('What should you do when a customer asks a question you cannot answer?', 'Politely explain and get the correct information or escalate it', 'Invent an answer', 'Ignore the customer', 'End the call immediately', 'A', 1),
('What is an important part of good customer service?', 'Listening carefully and responding clearly', 'Interrupting the customer', 'Speaking rudely', 'Avoiding questions', 'A', 1),
('How should a telecaller close a completed customer call?', 'Confirm the request and end the call politely', 'Disconnect without saying anything', 'Ask unrelated questions', 'Argue with the customer', 'A', 1);
