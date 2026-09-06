<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Portfolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- ============ HERO SECTION ============ -->
    <header>
        <div class="hero">
            <h1>👋 Hi, I'm <span class="highlight">Ahmed Raza Ur Rehman</span></h1>
            <p class="tagline">Backend Developer • API Builder • Problem Solver</p>
            <p class="sub">I build things with PHP, MySQL, and JavaScript.</p>
            <div class="hero-links">
                <a href="https://github.com/ahmed-raza-ur-rehman" class="btn" target="_blank">🐙 GitHub</a>
                <a href="admin.php" class="btn btn-secondary">✏️ Admin Panel</a>
            </div>
        </div>
    </header>

    <!-- ============ ABOUT SECTION ============ -->
    <section class="about">
        <h2>About Me</h2>
        <p>
            I'm a developer who loves building backend systems and APIs.
            This portfolio was built from scratch using PHP, MySQL, and vanilla JavaScript
            — including the API that powers it. Every project below is loaded dynamically
            from my own database through my own API.
        </p>
    </section>

    <!-- ============ PROJECTS SECTION ============ -->
    <section class="projects-section">
        <h2>My Projects</h2>
        <div id="project-grid">
            <p class="loading">⏳ Loading projects...</p>
        </div>
    </section>

    <!-- ============ CONTACT SECTION ============ -->
    <section class="contact">
        <h2>Get In Touch</h2>
        <p>Want to work together? Reach out!</p>
        <p>📧 your.email@example.com</p>
    </section>

    <!-- ============ FOOTER ============ -->
    <footer>
        <p>Built with ❤️ using PHP, MySQL & JavaScript | <a href="admin.php">Admin</a></p>
    </footer>

    <!-- ============ THE MAGIC: FETCH FROM YOUR API ============ -->
    <script>
        // This calls YOUR api.php and gets the projects as JSON
        fetch('api.php?action=read')
            .then(response => response.json())  // parse the JSON
            .then(projects => {
                const grid = document.getElementById('project-grid');

                // If no projects exist yet
                if (projects.length === 0) {
                    grid.innerHTML = '<p>No projects yet. Add one from the <a href="admin.php">admin panel</a>!</p>';
                    return;
                }

                // Build a card for each project
                grid.innerHTML = projects.map(project => `
                    <div class="card">
                        <h3>${project.title}</h3>
                        <p class="card-desc">${project.description}</p>
                        <div class="card-footer">
                            <span class="tech-badge">${project.tech || 'No tech listed'}</span>
                            ${project.link && project.link !== '#' 
                                ? `<a href="${project.link}" target="_blank" class="card-link">View Project →</a>` 
                                : ''
                            }
                        </div>
                    </div>
                `).join('');
            })
            .catch(error => {
                document.getElementById('project-grid').innerHTML = 
                    '<p style="color: #ef4444;">❌ Failed to load projects. Is your API running?</p>';
                console.error('API Error:', error);
            });
    </script>

</body>
</html>