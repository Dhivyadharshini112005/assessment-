-- F-Taxi Telecaller Assessment question banks
-- Run this once inside the existing `ftaxi_qa` database.
-- This does not touch employees, admins, videos, attempts, or Stage 2 results.
-- Stage 1: 20 active MCQs; employee receives random 10.
-- Stage 2: 10 active practical questions; employee receives random 5.

START TRANSACTION;

DELETE FROM stage1_questions;

INSERT INTO stage1_questions
(question, option_a, option_b, option_c, option_d, correct_option, active) VALUES
('Call வந்தவுடன் எப்படி attend செய்ய வேண்டும்? / How should you attend an incoming call?',
 'Call-ஐ reject செய்ய வேண்டும் / Reject the call',
 'Popup வந்தவுடன் Enter key-ஐ press செய்து பேச வேண்டும் / Press Enter when the popup appears and start speaking',
 'Customer மீண்டும் call செய்யும் வரை காத்திருக்க வேண்டும் / Wait for the customer to call again',
 'Call-ஐ disconnect செய்ய வேண்டும் / Disconnect the call', 'B', 1),

('Outstation booking-ல் waiting charge எப்படி கணக்கிடப்படும்? / How is the waiting charge calculated for an outstation booking?',
 'Waiting charge இல்லை / There is no waiting charge',
 '₹14 per minute / ₹14 per minute',
 '₹2.50 per minute / ₹2.50 per minute',
 'Usage-க்கு ஏற்ப ₹100 add செய்யப்படும் / ₹100 is added according to usage', 'D', 1),

('Local booking-ல் waiting charge எவ்வளவு? / What is the waiting charge for a local booking?',
 '₹1 per minute / ₹1 per minute',
 '₹4.50 per minute / ₹4.50 per minute',
 '₹0.30 per minute / ₹0.30 per minute',
 '₹2.50 per minute / ₹2.50 per minute', 'C', 1),

('வாடிக்கையாளர் அழைப்பை எடுத்தவுடன் முதலில் என்ன சொல்ல வேண்டும்? / What should you say first when answering a customer call?',
 'வணக்கம், உங்கள் சேவைக்காக [Name] பேசுகிறேன் / Hello, this is [Name], how may I help you?',
 'என்ன வேண்டும்? / What do you want?',
 'சொல்லுங்கள் / Tell me',
 'ஹலோ மட்டும் சொல்ல வேண்டும் / Say only Hello', 'A', 1),

('Customer-க்கு package-ஐ எப்படி சரியாக explain செய்ய வேண்டும்? / How should you properly explain the package to the customer?',
 'Hourly basis, KM, waiting charge மற்றும் extra KM charge ஆகியவற்றை explain செய்ய வேண்டும் / Explain hourly basis, KM, waiting charge and extra KM charges',
 'KM மட்டும் சொல்ல வேண்டும் / Tell only the KM',
 'Package details explain செய்ய வேண்டியதில்லை / No need to explain the package',
 'Hourly basis மட்டும் சொல்ல வேண்டும் / Tell only the hourly basis', 'A', 1),

('Local booking-ல் drop-ஐ விட கூடுதலாக செல்லும் ஒவ்வொரு KM-க்கும் எவ்வளவு charge? / What is the charge for each additional KM beyond the drop point in a local booking?',
 '₹14', '₹19', '₹22', '₹11', 'C', 1),

('Outstation booking-ல் extra KM charge எவ்வளவு? / What is the extra KM charge for an outstation booking?',
 '₹22', '₹14', '₹99', '₹26', 'B', 1),

('Customer-க்கு price சொல்லும்போது எப்படி சொல்ல வேண்டும்? / How should you explain the price to the customer?',
 'Exact price தான் என்று உறுதியாக சொல்ல வேண்டும் / Say confidently that it is the exact price',
 '“Approximate” / “தோராயமாக” என்று குறிப்பிட வேண்டும் / Mention that the price is approximate',
 'Price சொல்லக்கூடாது / Do not tell the price',
 'Driver தான் price சொல்ல வேண்டும் / The driver should tell the price', 'B', 1),

('Local booking-ல் 2KM-க்கான actual price எவ்வளவு? / What is the actual price for 2 KM in a local booking?',
 '₹14', '₹33', '₹99', '₹199', 'C', 1),

('Taxi booking எடுக்கும்போது எந்த இரண்டு முக்கியமான இடங்களை கேட்க வேண்டும்? / Which two important locations should you ask for when taking a taxi booking?',
 'Customer வீடு மற்றும் office / Customer home and office',
 'Customer hometown மற்றும் office / Customer hometown and office',
 'Driver location மற்றும் customer location / Driver location and customer location',
 'Pickup place மற்றும் Drop place / Pickup place and Drop place', 'D', 1),

('Booking-ஐ confirm செய்வதற்கு முன் எந்த தகவல்களை சரிபார்க்க வேண்டும்? / What should you verify before confirming a booking?',
 'Pickup, drop and other important booking details / Pickup, drop and other important booking details',
 'Customer age மட்டும் / Only customer age',
 'Vehicle colour மட்டும் / Only vehicle colour',
 'எதுவும் சரிபார்க்க வேண்டாம் / Nothing needs to be checked', 'A', 1),

('Customer பேசுவது புரியவில்லை என்றால் என்ன செய்ய வேண்டும்? / What should you do if you do not understand the customer?',
 'Polite-ஆக மீண்டும் சொல்லச் சொல்ல வேண்டும் / Politely ask the customer to repeat or clarify',
 'Guess செய்து பதில் சொல்ல வேண்டும் / Guess the answer',
 'Call-ஐ உடனே cut செய்ய வேண்டும் / Cut the call immediately',
 'Customer-ஐ argue செய்ய வேண்டும் / Argue with the customer', 'A', 1),

('Customer information-ஐ எப்படி handle செய்ய வேண்டும்? / How should customer information be handled?',
 'Private-ஆக வைத்திருந்து authorized person-க்கு மட்டும் share செய்ய வேண்டும் / Keep it private and share only when authorized',
 'யாரிடமும் share செய்யலாம் / Share it with anyone',
 'Online-ல் post செய்யலாம் / Post it online',
 'மற்ற customers-க்கு சொல்லலாம் / Tell other customers', 'A', 1),

('Customer ஒரு தகவலை கேட்டும் உங்களுக்கு தெரியவில்லை என்றால் என்ன செய்ய வேண்டும்? / What should you do if you do not know the answer to a customer question?',
 'Correct information-ஐ verify செய்து அல்லது escalate செய்து சொல்ல வேண்டும் / Verify the correct information or escalate it',
 'தவறான answer சொல்ல வேண்டும் / Give an incorrect answer',
 'Customer-ஐ ignore செய்ய வேண்டும் / Ignore the customer',
 'Call-ஐ உடனே disconnect செய்ய வேண்டும் / Disconnect immediately', 'A', 1),

('Customer-ஐ handle செய்யும்போது எந்த tone பயன்படுத்த வேண்டும்? / What tone should you use when handling a customer?',
 'Polite and professional / மரியாதையாகவும் professional-ஆகவும்',
 'Angry and loud / கோபமாகவும் சத்தமாகவும்',
 'Careless / கவனக்குறைவாக',
 'Silent / எதுவும் பேசாமல்', 'A', 1),

('Customer booking details-ஐ repeat செய்வதன் முக்கியத்துவம் என்ன? / Why should you repeat the booking details?',
 'Mistakes-ஐ avoid செய்து customer confirmation பெற / To avoid mistakes and get customer confirmation',
 'Call-ஐ நீட்டிக்க / To make the call longer',
 'Customer-ஐ confuse செய்ய / To confuse the customer',
 'தேவையில்லை / It is not needed', 'A', 1),

('Call-ஐ முடிக்கும்போது எப்படி close செய்ய வேண்டும்? / How should you close a completed call?',
 'Booking/request-ஐ confirm செய்து polite-ஆக close செய்ய வேண்டும் / Confirm the request and close politely',
 'எதுவும் சொல்லாமல் cut செய்ய வேண்டும் / Disconnect without saying anything',
 'Customer-ஐ argue செய்ய வேண்டும் / Argue with the customer',
 'Unrelated questions கேட்க வேண்டும் / Ask unrelated questions', 'A', 1),

('Customer complaint வந்தால் முதலில் என்ன செய்ய வேண்டும்? / What should you do first when a customer makes a complaint?',
 'Customer-ஐ கவனமாக கேட்டு issue-ஐ புரிந்துகொள்ள வேண்டும் / Listen carefully and understand the issue',
 'Customer-ஐ interrupt செய்ய வேண்டும் / Interrupt the customer',
 'உடனே blame செய்ய வேண்டும் / Blame the customer immediately',
 'Call-ஐ disconnect செய்ய வேண்டும் / Disconnect the call', 'A', 1),

('Booking details-ல் doubt இருந்தால் என்ன செய்ய வேண்டும்? / What should you do if there is a doubt in the booking details?',
 'Customer-யிடம் politely confirm செய்ய வேண்டும் / Politely confirm with the customer',
 'Guess செய்து booking செய்ய வேண்டும் / Guess and book',
 'Details-ஐ skip செய்ய வேண்டும் / Skip the details',
 'Call-ஐ reject செய்ய வேண்டும் / Reject the call', 'A', 1),

('Good customer service-ன் முக்கியமான பகுதி எது? / What is an important part of good customer service?',
 'Carefully listening and responding clearly / கவனமாக கேட்டு தெளிவாக பதிலளித்தல்',
 'Interrupting the customer / Customer-ஐ interrupt செய்தல்',
 'Speaking rudely / மரியாதையின்றி பேசுதல்',
 'Avoiding questions / கேள்விகளை தவிர்த்தல்', 'A', 1);

UPDATE stage2_questions SET active=0;

INSERT INTO stage2_questions (question, active) VALUES
('ஒரு வாடிக்கையாளர் நீண்ட நேரமாக Taxi-க்காக காத்திருக்கிறார். Driver இன்னும் வரவில்லை என்று கோபமாக பேசுகிறார். அவரை எவ்வாறு சமாளிப்பீர்கள்? / A customer has been waiting for a taxi for a long time. The driver has not arrived and the customer is angry. How would you handle the situation?', 1),
('ஒரு customer-க்கு அவசரமாக Taxi தேவைப்படுகிறது. Assigned driver திடீரென வர முடியாது என்று கூறுகிறார். Customer-க்கு என்ன கூறுவீர்கள்? அடுத்து என்ன செய்வீர்கள்? / A customer urgently needs a taxi, but the assigned driver suddenly cannot come. What would you tell the customer and what would you do next?', 1),
('நீங்கள் customer-க்கு தவறான தகவல் கொடுத்துவிட்டீர்கள். Customer அதை அறிந்து கோபமாக மீண்டும் call செய்கிறார். எவ்வாறு handle செய்வீர்கள்? / You accidentally gave incorrect information to a customer. The customer calls back angrily after discovering the mistake. How would you handle it?', 1),
('ஒரே நேரத்தில் இரண்டு customers உதவி கேட்கிறார்கள். ஒருவர் normal booking enquiry; மற்றொருவரின் Taxi ஏற்கனவே வந்துகொண்டிருக்கிறது ஆனால் urgent problem உள்ளது. யாரை முதலில் handle செய்வீர்கள்? ஏன்? / Two customers need help at the same time. One has a normal booking enquiry; the other has an urgent problem with a taxi already on the way. Whom would you handle first and why?', 1),
('ஒரு புதிய customer “Taxi booking செய்ய வேண்டும்; என்ன தகவல்கள் கொடுக்க வேண்டும் என்று தெரியவில்லை” என்று கூறுகிறார். அவரை எவ்வாறு guide செய்து booking complete செய்வீர்கள்? / A new customer says, “I want to book a taxi, but I do not know what information I need to provide.” How would you guide the customer and complete the booking?', 1),
('Customer ஒருவர் booking-ல் pickup location தவறாக பதிவு செய்யப்பட்டுள்ளதாக கூறுகிறார். Booking-ஐ சரியாக செய்ய நீங்கள் என்ன steps எடுப்பீர்கள்? / A customer says the pickup location in the booking is wrong. What steps would you take to correct the booking?', 1),
('ஒரு customer தொடர்ந்து பேசிக்கொண்டிருக்கிறார்; நீங்கள் booking details confirm செய்ய முடியவில்லை. Customer-ஐ மரியாதையாக handle செய்து தேவையான தகவல்களை எவ்வாறு பெறுவீர்கள்? / A customer keeps talking and you cannot confirm the booking details. How would you politely handle the conversation and obtain the required information?', 1),
('ஒரு customer price குறித்து திருப்தியில்லாமல் “வேறு taxi குறைந்த price-ல் கிடைக்கிறது” என்று கூறுகிறார். F-Taxi service-ஐ professional-ஆக எவ்வாறு explain செய்வீர்கள்? / A customer says another taxi is available at a lower price. How would you professionally explain the F-Taxi service?', 1),
('Customer complaint-ஐ solve செய்ய உங்களால் உடனடியாக முடியவில்லை என்றால், customer-க்கு என்ன சொல்லி issue-ஐ எவ்வாறு escalate செய்வீர்கள்? / If you cannot immediately solve a customer complaint, what would you tell the customer and how would you escalate the issue?', 1),
('ஒரு customer call-ல் மிகவும் அவசரமாக பேசுகிறார் மற்றும் booking details முழுமையாக சொல்லவில்லை. Accuracy-ஐ maintain செய்து booking எவ்வாறு handle செய்வீர்கள்? / A customer is in a hurry and does not provide complete booking details. How would you handle the booking while maintaining accuracy?', 1);

COMMIT;
