-- Users Table
INSERT INTO users (id, username, email, password, role) VALUES
(1, 'admin', 'admin@library.com', '$2y$10$k.3gUeqbJqBJUTK0jxAIluk8nrya.BqxgR5yE0ywJ7V9t4dp3tNZW', 'admin'),
(2, 'noureldien', 'noureldien@gmail.com', '$2y$10$r2UdDjwanTGPzUnfxNF7Q.NJBPYF7Ter1dqMBcz/.3c3YehYcmdgi', 'user'),
(3, 'soltan', 'soltan@gmail.com', '$2y$10$r7eu9lXlDgC2KVhrnMT/2eNeksoLnpm2mdYpEpIB.sxcb048SyFFC', 'user');

-- Books Table
INSERT INTO books (id, title, author, description, price, category, language, pages, book_file, book_image, status, uploaded_by) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 'A classic novel set in the Jazz Age.', 10.99, 'Fiction', 'English', 180, 'assets/uploads/books/gatsby.pdf', 'assets/uploads/images/gatsby.jpg', 'approved', 1),
(2, '1984', 'George Orwell', 'A dystopian novel about a totalitarian regime.', 12.99, 'Fiction', 'English', 328, 'assets/uploads/books/1984.pdf', 'assets/uploads/images/1984.jpg', 'approved', 2),
(3, 'A Brief History of Time', 'Stephen Hawking', 'A popular science book on cosmology.', 15.99, 'Non-Fiction', 'English', 256, 'assets/uploads/books/history_of_time.pdf', 'assets/uploads/images/history_of_time.jpg', 'approved', 2)
(4, 'To Kill a Mockingbird', 'Harper Lee', 'A novel about racial injustice in the Deep South.', 9.99, 'Fiction', 'English', 281, 'assets/uploads/books/mockingbird.pdf', 'assets/uploads/images/mockingbird.jpg', 'rejected', 3),
(5, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'A book about the history of humanity.', 18.99, 'Non-Fiction', 'English', 412, 'assets/uploads/books/sapiens.pdf', 'assets/uploads/images/sapiens.jpg', 'pending', 3);
-- Comments Table
INSERT INTO comments (id, book_id, user_id, comment, created_at) VALUES
(1, 1, 2, 'An incredible read! Highly recommend.', NOW()),
(2, 2, 3, 'A thought-provoking novel. Very relevant even today.', NOW()),
(3, 3, 2, 'Hawking explains complex topics so well. Amazing book.', NOW());
