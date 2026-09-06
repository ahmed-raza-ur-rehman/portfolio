<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — Portfolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- ============ HEADER ============ -->
    <header>
        <div class="hero" style="padding: 1.5rem 0;">
            <h1>⚙️ Admin Panel</h1>
            <a href="index.php" class="btn">← Back to Portfolio</a>
        </div>
    </header>

    <!-- ============ ADD / EDIT FORM ============ -->
    <section class="form-section">
        <h2 id="form-title">➕ Add New Project</h2>
        <form id="project-form">
            <input type="hidden" id="project-id" name="id">
            
            <label for="f-title">Project Title *</label>
            <input type="text" id="f-title" name="title" placeholder="e.g. Weather App" required>
            
            <label for="f-desc">Description *</label>
            <textarea id="f-desc" name="description" placeholder="What does it do? What did you learn?" required></textarea>
            
            <label for="f-tech">Tech Stack</label>
            <input type="text" id="f-tech" name="tech" placeholder="e.g. PHP, MySQL, JavaScript">
            
            <label for="f-link">Project Link</label>
            <input type="url" id="f-link" name="link" placeholder="https://...">
            
            <div class="form-buttons">
                <button type="submit" class="btn">💾 Save Project</button>
                <button type="button" id="cancel-edit" class="btn btn-secondary" style="display:none;" onclick="cancelEdit()">Cancel</button>
            </div>
        </form>
    </section>

    <!-- ============ EXISTING PROJECTS LIST ============ -->
    <section class="admin-list-section">
        <h2>📋 Your Projects</h2>
        <div id="admin-list">
            <p class="loading">⏳ Loading...</p>
        </div>
    </section>

    <!-- ============ JAVASCRIPT: ALL THE CRUD LOGIC ============ -->
    <script>
        // ---- LOAD ALL PROJECTS ----
        function loadProjects() {
            fetch('api.php?action=read')
                .then(r => r.json())
                .then(projects => {
                    const list = document.getElementById('admin-list');

                    if (projects.length === 0) {
                        list.innerHTML = '<p>No projects yet. Add your first one above! ☝️</p>';
                        return;
                    }

                    list.innerHTML = projects.map(p => `
                        <div class="admin-card">
                            <div class="admin-card-info">
                                <strong>${p.title}</strong>
                                <span class="tech-badge-small">${p.tech || 'No tech'}</span>
                                <p class="admin-card-desc">${p.description}</p>
                            </div>
                            <div class="admin-card-actions">
                                <button class="btn btn-small" onclick="editProject(${p.id})">✏️ Edit</button>
                                <button class="btn btn-delete btn-small" onclick="deleteProject(${p.id}, '${p.title.replace(/'/g, "\\'")}')">🗑️ Delete</button>
                            </div>
                        </div>
                    `).join('');
                });
        }

        // ---- CREATE OR UPDATE ----
        document.getElementById('project-form').addEventListener('submit', function(e) {
            e.preventDefault();  // Don't reload the page

            const id = document.getElementById('project-id').value;
            const action = id ? 'update' : 'create';  // If ID exists → update, else → create
            const formData = new FormData(this);

            fetch(`api.php?action=${action}`, {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.reset();                                    // Clear the form
                    document.getElementById('project-id').value = '';  // Clear hidden ID
                    document.getElementById('form-title').textContent = '➕ Add New Project';
                    document.getElementById('cancel-edit').style.display = 'none';
                    loadProjects();                                  // Refresh the list
                }
            });
        });

        // ---- EDIT (populate the form with existing data) ----
        function editProject(id) {
            // Fetch all projects, find the one with matching ID
            fetch('api.php?action=read')
                .then(r => r.json())
                .then(projects => {
                    const project = projects.find(p => p.id == id);
                    if (!project) return;

                    // Fill the form
                    document.getElementById('project-id').value = project.id;
                    document.getElementById('f-title').value = project.title;
                    document.getElementById('f-desc').value = project.description;
                    document.getElementById('f-tech').value = project.tech || '';
                    document.getElementById('f-link').value = project.link || '';

                    // Update UI
                    document.getElementById('form-title').textContent = '✏️ Edit Project';
                    document.getElementById('cancel-edit').style.display = 'inline-block';

                    // Scroll to form
                    document.getElementById('project-form').scrollIntoView({ behavior: 'smooth' });
                });
        }

        // ---- CANCEL EDIT ----
        function cancelEdit() {
            document.getElementById('project-form').reset();
            document.getElementById('project-id').value = '';
            document.getElementById('form-title').textContent = '➕ Add New Project';
            document.getElementById('cancel-edit').style.display = 'none';
        }

        // ---- DELETE ----
        function deleteProject(id, title) {
            if (!confirm(`Are you sure you want to delete "${title}"?`)) return;

            const formData = new FormData();
            formData.append('id', id);

            fetch('api.php?action=delete', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadProjects();  // Refresh the list
                }
            });
        }

        // ---- INITIAL LOAD ----
        loadProjects();
    </script>

</body>
</html>