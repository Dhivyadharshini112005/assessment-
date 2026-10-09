-- F-Taxi Stage 1: replace the current active questions with the final 10 bilingual MCQs.
-- Run this once inside the existing `ftaxi_qa` database.
-- This does NOT create/drop the database and does NOT touch employees, admins,
-- videos, attempts, Stage 2 questions, or Stage 2 results.

DELETE FROM stage1_questions;

INSERT INTO stage1_questions
(question, option_a, option_b, option_c, option_d, correct_option, active)
VALUES
(
'வாடிக்கையாளர் அழைப்பை எடுத்தவுடன் முதலில் என்ன சொல்ல வேண்டும்? / What should you say first when answering a customer call?',
'வணக்கம், உங்கள் சேவைக்காக [Name] பேசுகிறேன் / Vanakkam, ungal sevaikkaaga [Name] pesugiren',
'என்ன வேண்டும்? / Enna venum?',
'சொல்லுங்கள் / Sollunga',
'ஹலோ மட்டும் சொல்ல வேண்டும் / Say only Hello',
'A', 1
),
(
'Call வந்தவுடன் எப்படி attend செய்ய வேண்டும்? / How should you attend an incoming call?',
'Call-ஐ reject செய்ய வேண்டும் / Reject the call',
'Popup வந்தவுடன் Enter key-ஐ press செய்து பேச வேண்டும் / Press Enter when the popup appears and start speaking',
'Customer மீண்டும் call செய்யும் வரை காத்திருக்க வேண்டும் / Wait for the customer to call again',
'Call-ஐ disconnect செய்ய வேண்டும் / Disconnect the call',
'B', 1
),
(
'Taxi booking எடுக்கும்போது எந்த இரண்டு முக்கியமான இடங்களை கேட்க வேண்டும்? / Which two important locations should you ask for when taking a taxi booking?',
'Customer வீடு மற்றும் office / Customer home and office',
'Customer hometown மற்றும் office / Customer hometown and office',
'Driver location மற்றும் customer location / Driver location and customer location',
'Pickup place மற்றும் Drop place / Pickup place and Drop place',
'D', 1
),
(
'Local booking-ல் package-ஐ விட கூடுதலாக செல்லும் ஒவ்வொரு KM-க்கும் எவ்வளவு charge? / What is the charge for each extra KM in a local booking?',
'₹14',
'₹18',
'₹22',
'₹99',
'C', 1
),
(
'Outstation booking-ல் extra KM charge எவ்வளவு? / What is the extra KM charge for an outstation booking?',
'₹22',
'₹14',
'₹99',
'₹2.50',
'B', 1
),
(
'Local booking-ல் ஒரு KM-க்கான actual price எவ்வளவு? / What is the actual price per KM for a local booking?',
'₹14',
'₹22',
'₹99',
'₹100',
'C', 1
),
(
'Local மற்றும் Outstation booking-களுக்கான waiting charge எப்படி? / What are the waiting charges for Local and Outstation bookings?',
'Local ₹2.50 per minute; Outstation usage-க்கு ஏற்ப ₹100 add செய்யப்படும் / Local ₹2.50 per minute; ₹100 is added for Outstation based on usage',
'Local ₹14 per minute; Outstation ₹22 per minute',
'Local ₹99 per minute; Outstation waiting charge இல்லை / Local ₹99 per minute; no Outstation waiting charge',
'இரண்டிற்கும் ₹100 / ₹100 for both',
'A', 1
),
(
'Call-ஐ முடிக்கும்போது என்ன சொல்ல வேண்டும்? / What should you say when closing the call?',
'வாடிக்கையாளரிடம் எதுவும் சொல்லாமல் call-ஐ cut செய்ய வேண்டும் / Disconnect without saying anything',
'F-Taxi-ஐ அழைத்தமைக்கு நன்றி / F-Taxi-ஐ அழைத்தமைக்கு நன்றி',
'மீண்டும் call செய்ய வேண்டாம் என்று சொல்ல வேண்டும் / Tell the customer not to call again',
'சரி, bye மட்டும் சொல்ல வேண்டும் / Say only bye',
'B', 1
),
(
'Customer-க்கு package-ஐ எப்படி explain செய்ய வேண்டும்? / How should you explain the package to the customer?',
'Hourly basis மட்டும் சொல்ல வேண்டும் / Explain only the hourly basis',
'KM மட்டும் சொல்ல வேண்டும் / Explain only the KM',
'Hourly basis, KM, waiting charge மற்றும் extra KM charge ஆகியவற்றை explain செய்ய வேண்டும் / Explain hourly basis, KM, waiting charge and extra KM charges',
'Package details explain செய்ய வேண்டியதில்லை / No need to explain the package',
'C', 1
),
(
'Toll மற்றும் Parking charges எப்படி? / How are Toll and Parking charges handled?',
'Package amount-ல் included / Included in the package amount',
'Separate charges / தனியாக வசூலிக்கப்படும்',
'Driver மட்டும் pay செய்ய வேண்டும் / Driver should pay only',
'Customer pay செய்ய வேண்டாம் / Customer does not have to pay',
'B', 1
);
