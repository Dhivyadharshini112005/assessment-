-- Fix for the existing ftaxi_qa database.
-- The Taxi booking locations question was displayed with
-- Pickup place / Drop place as option D, but its stored correct answer
-- could still be B from the earlier seed data.
UPDATE stage1_questions
SET correct_option = 'D'
WHERE question LIKE '%Taxi booking%இரண்டு முக்கியமான இடங்களை கேட்க வேண்டும்%'
  AND option_d LIKE '%Pickup place%Drop place%';

-- Verify the repaired row.
SELECT id, question, option_a, option_b, option_c, option_d, correct_option
FROM stage1_questions
WHERE question LIKE '%Taxi booking%இரண்டு முக்கியமான இடங்களை கேட்க வேண்டும்%';
