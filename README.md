<!-- Header -->
<h1 style="text-align:center; font-size:3em; margin:30px 0 10px; color:#1a202c; font-family:'Segoe UI', sans-serif;">
  📘 Webist
</h1>

<p style="text-align:center; font-size:1.2em; color:#4a5568; font-family:'Segoe UI', sans-serif;">
  A modern school behavior management platform for tracking students across brigades and infirmary services.
</p>

<!-- Banner -->
<p style="text-align:center; margin:30px 0;">
  <img src="https://via.placeholder.com/900x300?text=Webist+Dashboard+Preview" alt="Webist Banner"
       style="max-width:100%; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
</p>

<hr style="border:none; border-top:1px solid #e2e8f0; margin:40px 0;">

<!-- Overview -->
<h2 style="color:#2d3748; font-family:'Segoe UI', sans-serif;">🚀 Overview</h2>
<p style="color:#4a5568; line-height:1.7; font-size:1em;">
  <strong>Webist</strong> is a role-based school behavior management system. It supports officers and medical staff in managing student infractions, reports, and medical follow-ups. Built with Laravel 12, Livewire, and Reverb, it features real-time updates and modern UI components.
</p>

<!-- Branching Strategy -->
<h2 style="color:#2d3748; font-family:'Segoe UI', sans-serif; margin-top:40px;">🌱 Branching Strategy</h2>
<div style="background-color:#fefcbf; padding:15px 20px; border-left:6px solid #ecc94b; border-radius:6px; font-size:1em; line-height:1.6;">
  ⚠️ <strong>Note for contributors:</strong><br>
  Development takes place in the <code style="background:#fef9c3; padding:2px 5px; border-radius:4px;">dev</code> branch.<br>
  The <code style="background:#fef9c3; padding:2px 5px; border-radius:4px;">main</code> branch is reserved for production-ready releases only.
</div>

<ul style="margin-top:15px; color:#2d3748; font-family:'Segoe UI', sans-serif;">
  <li><strong style="color:green;">main</strong> – Production-ready code only</li>
  <li><strong style="color:orange;">dev</strong> – Active development and features</li>
</ul>

<!-- Tech Stack -->
<h2 style="color:#2d3748; font-family:'Segoe UI', sans-serif; margin-top:40px;">🛠 Tech Stack</h2>
<ul style="line-height:1.8; color:#4a5568;">
  <li>PHP (Laravel 12)</li>
  <li>Livewire + Alpine.js</li>
  <li>Tailwind CSS</li>
  <li>Laravel Reverb + Echo (Real-time Broadcasting)</li>
  <li>MySQL Database</li>
</ul>

<!-- Features -->
<h2 style="color:#2d3748; font-family:'Segoe UI', sans-serif; margin-top:40px;">📦 Features</h2>
<ul style="line-height:1.8; color:#4a5568;">
  <li>🩺 Infirmary module for medical staff (Medecin, Psychologue, Dentiste)</li>
  <li>🧾 Student report system for brigade officers</li>
  <li>📊 Role-based dashboard with statistics by grade, section, or user</li>
  <li>🔐 Access control for each role</li>
  <li>🔔 Real-time notifications for new events</li>
</ul>

<!-- Getting Started -->
<h2 style="color:#2d3748; font-family:'Segoe UI', sans-serif; margin-top:40px;">💻 Getting Started</h2>
<pre style="background:#f7fafc; border:1px solid #e2e8f0; padding:16px; border-radius:6px; color:#2d3748; font-size:0.95em;">
git clone https://github.com/islemhdd/webist.git
cd webist
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
</pre>

<p style="color:#718096;">Visit <code style="background:#edf2f7; padding:2px 6px; border-radius:4px;">http://localhost:8000</code> in your browser.</p>

<!-- Contributing -->
<h2 style="color:#2d3748; font-family:'Segoe UI', sans-serif; margin-top:40px;">🤝 Contribution Workflow</h2>
<ol style="color:#4a5568; line-height:1.8;">
  <li>Checkout <code>dev</code>:
    <pre style="background:#f7fafc; padding:10px; border:1px solid #e2e8f0; border-radius:5px;">git checkout dev</pre>
  </li>
  <li>Create your feature branch:
    <pre style="background:#f7fafc; padding:10px; border:1px solid #e2e8f0; border-radius:5px;">git checkout -b feature/my-feature</pre>
  </li>
  <li>Commit and push:
    <pre style="background:#f7fafc; padding:10px; border:1px solid #e2e8f0; border-radius:5px;">git commit -m "Add feature"
git push origin feature/my-feature</pre>
  </li>
  <li>Open a pull request into <code>dev</code>.</li>
</ol>

<!-- Roles -->
<h2 style="color:#2d3748; font-family:'Segoe UI', sans-serif; margin-top:40px;">🧑‍💼 Role-Based Access</h2>
<table style="width:100%; border-collapse:collapse; font-family:'Segoe UI', sans-serif; font-size:0.95em;">
  <thead>
    <tr style="background-color:#f0f0f0;">
      <th style="padding:10px; border:1px solid #ddd; text-align:left;">Role</th>
      <th style="padding:10px; border:1px solid #ddd; text-align:left;">Access</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="padding:10px; border:1px solid #ddd;">Medecin / Psychologue / Dentiste</td>
      <td style="padding:10px; border:1px solid #ddd;">Infirmary dashboard and medical follow-ups</td>
    </tr>
    <tr>
      <td style="padding:10px; border:1px solid #ddd;">Chef de brigade / Compagnie</td>
      <td style="padding:10px; border:1px solid #ddd;">Brigade reports, daily tracking, real-time stats</td>
    </tr>
    <tr>
      <td style="padding:10px; border:1px solid #ddd;">Directeur des études</td>
      <td style="padding:10px; border:1px solid #ddd;">Access to all dashboards and system-wide stats</td>
    </tr>

  </tbody>
</table>





<!-- Footer -->
<hr style="border:none; border-top:1px solid #e2e8f0; margin:40px 0;">

<p style="text-align:center; color:#a0aec0; font-size:1em;">
  Built with ❤️ using Laravel, Livewire, and Tailwind CSS
</p>
