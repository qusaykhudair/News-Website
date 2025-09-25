-- Global News Network Database Structure
-- Create database and tables with sample data

CREATE DATABASE IF NOT EXISTS news_website;
USE news_website;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: categories
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: articles
CREATE TABLE IF NOT EXISTS articles (
    article_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(500) DEFAULT 'https://images.pexels.com/photos/518543/pexels-photo-518543.jpeg',
    category_id INT,
    author_id INT,
    published_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_author (author_id),
    INDEX idx_published (published_date),
    FULLTEXT INDEX idx_search (title, content)
);

-- Table: comments
CREATE TABLE IF NOT EXISTS comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    user_id INT,
    comment_text TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(article_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_article (article_id),
    INDEX idx_user (user_id)
);

-- Table: newsletter_subscribers (Bonus feature)
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    subscriber_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);


-- Insert sample data

-- Insert categories
INSERT INTO categories (category_name) VALUES 
('Politics'),
('Technology'),
('Sports'),
('World');

-- Insert admin user (password: admin123)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@globalnews.com', '$2y$10$eno4BkzmlK06w2k4XeYKoOUv3glSO.I0HBRXym46xm/QfYX5jyt9y', 'admin'),
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Insert sample articles
INSERT INTO articles (title, content, image_url, category_id, author_id) VALUES 
(
    'Breaking: Major Technology Breakthrough Announced', 
    'Scientists at a leading research institute have announced a groundbreaking discovery that could revolutionize the way we interact with technology. The new innovation promises to enhance efficiency and accessibility across multiple industries.

The research team, led by Dr. Sarah Chen, spent three years developing this cutting-edge solution. "This breakthrough represents a significant leap forward in our understanding of computational systems," said Dr. Chen during yesterday\'s press conference.

The technology utilizes advanced algorithms and machine learning techniques to process information at unprecedented speeds. Early testing shows improvements of up to 300% in processing efficiency compared to current methods.

Major tech companies have already expressed interest in licensing this technology. Industry experts predict that this innovation could lead to more intuitive user interfaces and smarter automated systems.

The research was funded by a coalition of universities and private investors, highlighting the collaborative nature of modern scientific advancement. The team plans to publish their findings in next month\'s Journal of Advanced Computing.

Applications for this technology span numerous sectors, including healthcare, education, transportation, and communication. The potential impact on everyday life could be transformative, making technology more accessible to people with disabilities and improving overall user experience.

Commercial applications are expected to begin rolling out within the next 18 months, pending regulatory approval and further testing phases.',
    'https://images.pexels.com/photos/373543/pexels-photo-373543.jpeg',
    2, 1
),
(
    'Global Climate Summit Reaches Historic Agreement',
    'World leaders gathered at the International Climate Summit have reached a historic agreement on carbon emissions reduction, marking a significant milestone in global environmental policy.

The three-day summit, held in Geneva, brought together representatives from 195 countries to address the urgent challenges of climate change. The final agreement includes ambitious targets for reducing greenhouse gas emissions by 50% within the next decade.

President Maria Santos of the European Climate Alliance called the agreement "a turning point in humanity\'s fight against climate change." The accord establishes binding commitments for developed nations while providing support mechanisms for developing countries.

Key provisions of the agreement include substantial investments in renewable energy infrastructure, with a commitment of $500 billion in funding over the next five years. The plan also outlines strategies for transitioning away from fossil fuels while ensuring economic stability.

Environmental groups have praised the agreement as "the most comprehensive climate action plan ever adopted." However, some critics argue that the targets may not be ambitious enough to prevent the most severe effects of climate change.

The implementation phase will begin immediately, with quarterly progress reviews scheduled for all participating nations. A dedicated monitoring body will oversee compliance and provide technical assistance where needed.

This landmark agreement represents years of negotiation and compromise, demonstrating unprecedented international cooperation on environmental issues. The success of this initiative could serve as a model for addressing other global challenges requiring collective action.',
    'https://images.pexels.com/photos/221078/pexels-photo-221078.jpeg',
    1, 1
),
(
    'Championship Finals Set Record Viewership Numbers',
    'The recently concluded championship finals have shattered all previous viewership records, with over 2.8 billion people tuning in worldwide to watch the thrilling conclusion of this year\'s tournament.

The dramatic final match between defending champions and the surprising underdogs kept viewers on the edge of their seats for nearly four hours. The game featured remarkable performances from both teams, with several record-breaking individual achievements.

Star player Alex Rodriguez delivered what many are calling the performance of a lifetime, scoring the winning goal in the final minutes of overtime. "This moment is what every athlete dreams of," Rodriguez said during the post-game interview.

Broadcasting networks reported that streaming platforms experienced unprecedented traffic, with some services temporarily overwhelmed by the massive global audience. Social media engagement reached historic levels, with over 500 million posts related to the championship.

The economic impact of the tournament has been substantial, generating an estimated $12 billion in revenue across various sectors including tourism, merchandise, and broadcasting rights. Host cities reported hotel occupancy rates exceeding 95% throughout the tournament period.

International Olympic Committee officials noted that this tournament\'s success sets a new standard for global sporting events. The innovative use of technology to enhance viewer experience, including augmented reality features and multi-angle streaming options, contributed significantly to the record-breaking audience engagement.

Plans are already underway for next year\'s tournament, with several cities competing to host the event. The organizing committee promises even more exciting innovations to maintain the momentum generated by this year\'s spectacular success.',
    'https://images.pexels.com/photos/274422/pexels-photo-274422.jpeg',
    3, 1
),
(
    'New Entertainment Complex Opens Downtown',
    'The city\'s largest entertainment complex officially opened its doors yesterday, featuring state-of-the-art facilities and a diverse range of attractions designed to appeal to visitors of all ages.

The $2.8 billion development spans 15 acres in the heart of downtown and includes multiple theaters, restaurants, shopping areas, and interactive exhibits. The centerpiece is a 4,000-seat multipurpose arena that will host concerts, theatrical performances, and sporting events.

Mayor Jennifer Walsh cut the ribbon at the opening ceremony, calling the complex "a game-changer for our city\'s cultural landscape." The development is expected to create over 8,000 jobs and attract millions of visitors annually.

The complex features cutting-edge technology throughout, including holographic displays, virtual reality experiences, and an advanced sound system that adapts to different types of performances. Sustainability was a key consideration, with the entire facility powered by renewable energy sources.

Local business owners are optimistic about the economic benefits the complex will bring to the surrounding area. Restaurant owner Carlos Martinez noted, "We\'ve already seen increased foot traffic, and this is just the beginning."

The entertainment complex also includes educational components, with interactive exhibits showcasing local history and culture. A dedicated space for emerging artists provides opportunities for local talent to showcase their work alongside internationally recognized performers.

Advance ticket sales for upcoming shows have exceeded expectations, with several performances already sold out through the end of the year. The complex represents a significant investment in the city\'s future as a major cultural destination.',
    'https://images.pexels.com/photos/2747449/pexels-photo-2747449.jpeg',
    4, 1
),
(
    'Stock Market Reaches All-Time High Amid Economic Optimism',
    'Global stock markets closed at record highs yesterday as investors responded positively to encouraging economic indicators and corporate earnings reports that exceeded analyst expectations.

The benchmark index gained 3.2% during trading, marking the fifth consecutive day of growth and pushing the market to unprecedented levels. Technology and healthcare sectors led the rally, with several companies announcing better-than-expected quarterly results.

Financial analysts attribute the surge to a combination of factors, including improved consumer confidence, robust employment data, and positive developments in international trade relations. "We\'re seeing a perfect storm of positive economic indicators," said chief economist Dr. Robert Kim.

Major corporations reported strong earnings, with many citing increased demand for their products and services. The technology sector, in particular, benefited from continued growth in digital transformation initiatives across industries.

Consumer spending data released earlier this week showed a 4.5% increase compared to the same period last year, indicating strong economic fundamentals. Unemployment rates have fallen to their lowest levels in over a decade, contributing to increased consumer confidence.

International markets also participated in the rally, with European and Asian exchanges posting significant gains. Currency markets remained stable, reflecting confidence in the overall global economic outlook.

However, some economists urge caution, noting that market volatility remains a possibility given ongoing geopolitical uncertainties. "While current indicators are positive, investors should maintain diversified portfolios," advised financial advisor Sarah Thompson.

The Federal Reserve is closely monitoring these developments as they consider future monetary policy decisions. The central bank\'s next meeting is scheduled for next month, where interest rate adjustments may be discussed.',
    'https://images.pexels.com/photos/534216/pexels-photo-534216.jpeg',
    5, 1
),
(
    'Revolutionary Medical Treatment Shows Promise in Clinical Trials',
    'Researchers at the National Medical Institute have announced promising results from Phase III clinical trials of a revolutionary treatment that could transform patient care for millions of people worldwide.

The innovative therapy, developed over eight years of research, targets previously untreatable conditions using advanced genetic modification techniques. Initial results show a 89% success rate among trial participants, far exceeding researchers\' expectations.

Dr. Michael Foster, lead researcher on the project, described the results as "truly groundbreaking." The treatment approach represents a fundamental shift in medical methodology, focusing on addressing root causes rather than managing symptoms.

The clinical trial involved 2,500 participants across 15 countries, making it one of the largest international medical studies ever conducted. Patients reported significant improvements in their condition within weeks of beginning treatment, with minimal side effects observed.

Regulatory agencies in multiple countries are fast-tracking the approval process given the treatment\'s potential impact on public health. The European Medicines Agency has designated it as a "breakthrough therapy," expediting the review timeline.

Medical professionals worldwide have expressed excitement about the treatment\'s potential applications. "This could fundamentally change how we approach patient care," said Dr. Lisa Chen, director of the International Medical Association.

The pharmaceutical company developing the treatment has committed to making it accessible in developing countries through a tiered pricing structure. Plans are already underway to establish manufacturing facilities on multiple continents to ensure global availability.

If approved, the treatment could become available to patients within the next 12-18 months. The research team continues to monitor long-term effects and is exploring applications for related conditions.',
    'https://images.pexels.com/photos/356040/pexels-photo-356040.jpeg',
    6, 1
),
(
    'Space Mission Discovers Evidence of Water on Distant Planet',
    'Scientists analyzing data from the latest deep space mission have discovered compelling evidence of liquid water on a planet located 47 light-years from Earth, marking a significant milestone in the search for potentially habitable worlds.

The discovery was made using advanced spectroscopic analysis of atmospheric data collected by the Kepler Advanced Space Telescope. The planet, designated K2-438b, shows clear signatures of water vapor in its atmosphere, along with conditions that could support liquid water on its surface.

Mission Director Dr. Amanda Rodriguez announced the findings at yesterday\'s press conference, calling it "one of the most significant discoveries in exoplanet research." The planet orbits within the habitable zone of its star, where temperatures could allow liquid water to exist.

The research team used cutting-edge machine learning algorithms to process vast amounts of spectral data, identifying specific wavelengths that indicate the presence of water molecules. This technological approach has revolutionized how scientists analyze atmospheric compositions of distant worlds.

K2-438b is approximately 1.8 times the size of Earth and receives similar amounts of solar radiation from its host star. Computer models suggest the planet could have surface temperatures ranging from -10°C to 45°C, depending on atmospheric composition and surface conditions.

The discovery has significant implications for astrobiology and the search for extraterrestrial life. While the presence of water doesn\'t guarantee life, it\'s considered a crucial prerequisite for life as we understand it.

Future missions are already being planned to study K2-438b in greater detail. The James Webb Space Telescope will focus on analyzing the planet\'s atmospheric composition more precisely, potentially detecting other biosignatures.

This breakthrough demonstrates the rapid advancement in exoplanet detection capabilities and brings humanity one step closer to answering the fundamental question of whether we are alone in the universe.',
    'https://images.pexels.com/photos/41162/moon-landing-apollo-11-nasa-buzz-aldrin-41162.jpeg',
    7, 1
),
(
    'International Trade Summit Yields New Economic Partnerships',
    'The World Trade Summit concluded yesterday with the announcement of several major economic partnerships that are expected to boost global commerce and create millions of jobs worldwide.

Representatives from 75 countries participated in the five-day summit, negotiating trade agreements that will reduce barriers and promote economic cooperation. The agreements cover sectors ranging from technology and manufacturing to agriculture and services.

The largest agreement involves a trade partnership between the Pacific Economic Alliance and the European Trade Consortium, creating a free trade zone covering over 40% of global GDP. This partnership is projected to increase trade volume by $2.3 trillion over the next decade.

Trade Minister Elena Vasquez, who led the negotiations, described the agreements as "a new era of economic cooperation." The deals include provisions for environmental protection and labor standards, addressing concerns raised by advocacy groups.

Small and medium-sized enterprises are expected to benefit significantly from reduced trade barriers and simplified export procedures. New digital platforms will be established to help smaller companies access international markets more easily.

The agreements also include substantial investments in infrastructure development, with $800 billion committed to improving transportation and communication networks across participating countries. This infrastructure development is expected to create millions of construction and engineering jobs.

Environmental sustainability was a key consideration in all negotiations, with binding commitments to reduce carbon emissions from international shipping and transportation. Green technology transfer provisions will help developing countries adopt cleaner industrial processes.

Implementation of these agreements will begin immediately, with full benefits expected to be realized within three to five years. Economic analysts predict positive impacts on global growth rates and employment levels.',
    'https://images.pexels.com/photos/3183197/pexels-photo-3183197.jpeg',
    8, 1
),
(
    'Artificial Intelligence Breakthrough: Machines Learn Human Emotions',
    'Researchers at the Institute for Advanced AI have achieved a major breakthrough in emotional artificial intelligence, developing systems that can accurately recognize, interpret, and respond to human emotions with unprecedented precision.

The new AI system, called EmpathyNet, uses advanced neural networks trained on millions of hours of human interaction data. The system can analyze facial expressions, voice patterns, body language, and contextual information to understand emotional states with 94% accuracy.

Dr. Sarah Kim, the project\'s lead scientist, explained that this advancement could revolutionize how humans interact with technology. "We\'re moving beyond simple command-response interactions to genuine emotional understanding," she said during the research presentation.

The technology has immediate applications in healthcare, where AI assistants could provide emotional support to patients and help healthcare workers identify signs of depression or anxiety. Mental health professionals are particularly excited about the potential for 24/7 emotional monitoring and support.

Educational applications are equally promising, with AI tutors able to adapt their teaching methods based on students\' emotional responses. This personalized approach could significantly improve learning outcomes and student engagement.

The research team conducted extensive testing to ensure the AI system respects privacy and cultural differences in emotional expression. Safeguards have been built in to prevent misuse and protect sensitive emotional data.

Major technology companies have already expressed interest in licensing the technology. However, the researchers emphasize the importance of ethical implementation and are working with policymakers to establish appropriate guidelines.

The breakthrough opens up possibilities for more intuitive human-computer interfaces and could lead to AI companions that provide genuine emotional support to elderly individuals and those with social isolation.',
    'https://images.pexels.com/photos/8386440/pexels-photo-8386440.jpeg',
    2, 1
),
(
    'Olympic Training Facility Unveils Cutting-Edge Technology',
    'The newly opened National Olympic Training Center has introduced revolutionary training technologies that promise to give athletes unprecedented insights into their performance and help them achieve new levels of excellence.

The $450 million facility features advanced biomechanical analysis systems, virtual reality training environments, and AI-powered coaching assistants that provide real-time feedback to athletes across all Olympic disciplines.

The centerpiece of the facility is the Performance Analytics Lab, where athletes\' movements are captured by hundreds of high-speed cameras and analyzed by supercomputers. This data helps identify micro-improvements that can lead to significant performance gains.

Olympic champion Maria Santos, who tested the new systems, described the experience as "like having the perfect coach who never misses anything." The technology can detect technique variations as small as one degree in joint angles.

Virtual reality systems allow athletes to train in simulated competition environments, helping them prepare for the psychological pressures of major competitions. The VR systems can recreate the exact conditions of Olympic venues, including crowd noise and lighting.

Recovery and injury prevention are also enhanced through advanced monitoring systems that track athletes\' physiological responses to training. The facility includes cryotherapy chambers, hyperbaric oxygen chambers, and precision nutrition programs tailored to individual athletes.

The training center will host athletes from multiple countries as part of international cooperation agreements. This approach aims to raise the overall level of Olympic competition while fostering goodwill between nations.

Sports scientists from around the world are collaborating on research projects at the facility, with findings shared to benefit the global athletic community. The center represents a new era in sports science and athletic performance optimization.',
    'https://images.pexels.com/photos/209969/pexels-photo-209969.jpeg',
    3, 1
);

-- Insert sample comments
INSERT INTO comments (article_id, user_id, comment_text) VALUES 
(1, 2, 'This is incredible news! Can\'t wait to see how this technology will be implemented.'),
(1, 3, 'Amazing breakthrough. The future of computing looks very promising.'),
(2, 2, 'Finally, world leaders are taking climate change seriously. This gives me hope.'),
(2, 3, 'Great step forward, but we need to ensure these commitments are actually kept.'),
(3, 2, 'What an incredible match! Rodriguez was absolutely phenomenal.'),
(4, 3, 'The new entertainment complex looks amazing. Planning to visit next weekend!'),
(5, 2, 'Good news for investors, but I hope this growth is sustainable.'),
(6, 3, 'Medical breakthroughs like this give hope to so many patients and families.');

-- Create indexes for better performance
CREATE INDEX idx_articles_published ON articles(published_date DESC);
CREATE INDEX idx_articles_category_published ON articles(category_id, published_date DESC);
CREATE INDEX idx_comments_article ON comments(article_id, timestamp DESC);

-- Create views for common queries
CREATE VIEW latest_articles AS
SELECT 
    a.article_id,
    a.title,
    a.content,
    a.image_url,
    a.published_date,
    c.category_name,
    u.username as author_name
FROM articles a
LEFT JOIN categories c ON a.category_id = c.category_id
LEFT JOIN users u ON a.author_id = u.user_id
ORDER BY a.published_date DESC;

CREATE VIEW article_stats AS
SELECT 
    a.article_id,
    a.title,
    c.category_name,
    a.published_date,
    COUNT(co.comment_id) as comment_count
FROM articles a
LEFT JOIN categories c ON a.category_id = c.category_id
LEFT JOIN comments co ON a.article_id = co.article_id
GROUP BY a.article_id, a.title, c.category_name, a.published_date;

-- Default admin user credentials:
-- Username: admin
-- Password: admin123
-- Email: admin@globalnews.com