-- Users Table
INSERT INTO users (id, username, email, password, role) VALUES
(1, 'admin', 'admin@library.com', '$2y$10$k.3gUeqbJqBJUTK0jxAIluk8nrya.BqxgR5yE0ywJ7V9t4dp3tNZW', 'admin'),
(2, 'noureldien', 'noureldien@gmail.com', '$2y$10$r2UdDjwanTGPzUnfxNF7Q.NJBPYF7Ter1dqMBcz/.3c3YehYcmdgi', 'user'),
(3, 'soltan', 'soltan@gmail.com', '$2y$10$r7eu9lXlDgC2KVhrnMT/2eNeksoLnpm2mdYpEpIB.sxcb048SyFFC', 'user');

-- Books Table
INSERT INTO books (id, title, author, description, price, category, language, pages, book_file, book_image, status, uploaded_by) VALUES
-- Approved Books
(1, 'Atomic Habits', 'James Clear', 'A guide to building good habits and breaking bad ones.', 16.99, 'Self-Help', 'English', 320, './assets/uploads/books/Atomic_habits.pdf', './assets/uploads/images/atomic-habits_-tiny-changes-remarkable-results-james-clear.jpg', 'approved', 2),
(2, 'Getting Things Done', 'David Allen', 'The art of stress-free productivity.', 14.99, 'Self-Help', 'English', 267, './assets/uploads/books/getting-things-done-the-art-of-stress-free-productivity.pdf', './assets/uploads/images/getting-things-done-the-art-of-stress-free-productivit.jpg', 'approved', 3),
(3, 'Pür-Dikkat', 'Cal Newport', 'Focus and deep work in a distracted world.', 13.99, 'Self-Help', 'Turkish', 288, './assets/uploads/books/Pür-dikkat.pdf', './assets/uploads/images/Pür-dikkat.jpeg', 'approved', 2),
(4, 'الرحيق المختوم', 'صفي الرحمن المباركفوري', 'Biography of Prophet Muhammad (PBUH).', 10.99, 'Islamic Studies', 'Arabic', 340, './assets/uploads/books/الرحيق-المختوم.pdf', './assets/uploads/images/الرحيق-المختوم.jpg', 'approved', 2),
(5, 'The Psychology of Money', 'Morgan Housel', 'Timeless lessons on wealth, greed, and happiness.', 18.99, 'Finance', 'English', 252, './assets/uploads/books/The_Psychology_of_Money.pdf', './assets/uploads/images/The_Psychology_of_Money.jpg', 'approved', 3),
(6, 'العقيدة في ضوء الكتاب والسنة : 4 الرسل والرسالات', 'د. عمر سليمان الأشقر', 'Theology in light of the Quran and Sunnah.', 11.99, 'Islamic Studies', 'Arabic', 310, './assets/uploads/books/العقيدة-في-ضوء-الكتاب-والسنة-4-الرسل-والرسالات.pdf', './assets/uploads/images/العقيدة-في-ضوء-الكتاب-والسنة-4-الرسل-والرسالات.jpeg', 'approved', 3),
(7, 'The 4-Hour Workweek', 'Tim Ferriss', 'Escape 9-5, live anywhere, and join the new rich.', 19.99, 'Self-Help', 'English', 308, './assets/uploads/books/the-4-hour-workweek.pdf', './assets/uploads/images/the-4-hour-workweek.jpg', 'approved', 2),

-- Pending Book
(8, 'The Millionaire Next Door', 'Thomas J. Stanley and William D. Danko', 'The surprising secrets of America’s wealthy.', 15.99, 'Finance', 'English', 275, './assets/uploads/books/The-Millionaire-Next-Door.pdf', './assets/uploads/images/The-Millionaire-Next-Door.jpg', 'pending', 3),

-- Rejected Book
(9, 'Your Money Or Your Life', 'Vicki Robin', 'Transform your relationship with money.', 17.99, 'Finance', 'English', 384, './assets/uploads/books/Your-Money-Or-Your-Life.pdf', './assets/uploads/images/Your-Money-Or-Your-Life.jpg', 'rejected', 3);

-- Comments Table
INSERT INTO comments (id, book_id, user_id, comment, created_at) VALUES
(1, 1, 2, 'An incredible read! Highly recommend.', NOW()),
(2, 2, 3, 'A thought-provoking novel. Very relevant even today.', NOW()),
(3, 3, 2, 'Hawking explains complex topics so well. Amazing book.', NOW());
