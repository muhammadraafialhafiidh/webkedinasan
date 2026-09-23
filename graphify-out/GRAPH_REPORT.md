# Graph Report - .  (2026-08-03)

## Corpus Check
- 178 files · ~61,838 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 781 nodes · 1221 edges · 109 communities (94 shown, 15 thin omitted)
- Extraction: 85% EXTRACTED · 15% INFERRED · 0% AMBIGUOUS · INFERRED: 178 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Documentation & Requirements (C0)
- CMS Management (C1)
- CMS Management (C2)
- Documentation & Requirements (C3)
- CMS Management (C4)
- Community 5
- Documentation & Requirements (C6)
- CMS Management (C7)
- Community 8
- CMS Management (C9)
- Community 10
- Community 11
- CMS Management (C12)
- CMS Management (C13)
- CMS Management (C14)
- Community 15
- Documentation & Requirements (C16)
- Community 17
- Community 18
- Automated Tests Suite (C19)
- Blade Views & UI Components (C20)
- Blade Views & UI Components (C45)
- Blade Views & UI Components (C48)
- Blade Views & UI Components (C49)
- Blade Views & UI Components (C50)
- Blade Views & UI Components (C51)
- Blade Views & UI Components (C52)
- Blade Views & UI Components (C53)
- Blade Views & UI Components (C54)
- Blade Views & UI Components (C55)
- Blade Views & UI Components (C56)
- Blade Views & UI Components (C57)

## God Nodes (most connected - your core abstractions)
1. `Controller` - 59 edges
2. `ActivityLog` - 57 edges
3. `User` - 32 edges
4. `News` - 17 edges
5. `Service` - 17 edges
6. `GalleryAlbum` - 14 edges
7. `NewsCategory` - 14 edges
8. `PenanggungJawab` - 14 edges
9. `ServiceCategory` - 14 edges
10. `GalleryVideo` - 13 edges

## Surprising Connections (you probably didn't know these)
- `AuthController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Cms/AuthController.php → app/Http/Controllers/Controller.php
- `CmsActivityLogController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Cms/CmsActivityLogController.php → app/Http/Controllers/Controller.php
- `CmsAlbumController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Cms/CmsAlbumController.php → app/Http/Controllers/Controller.php
- `CmsBannerController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Cms/CmsBannerController.php → app/Http/Controllers/Controller.php
- `CmsContactController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Cms/CmsContactController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (109 total, 15 thin omitted)

### Community 0 - "Documentation & Requirements (C0)"
Cohesion: 0.02
Nodes (84): 1. Gambaran Umum Proyek, 1.1 Tujuan Sistem, 2. Tech Stack, 3. Hak Akses & Role, 3.1 Daftar Role, 3.2 Matriks Hak Akses, 4. Kebutuhan Fungsional, 4.1 Halaman Publik (+76 more)

### Community 1 - "CMS Management (C1)"
Cohesion: 0.08
Nodes (17): CmsDocumentController, CmsOrganizationController, CmsProfileController, CmsRoleController, DashboardController, Controller, DocumentController, GalleryVideoController (+9 more)

### Community 2 - "CMS Management (C2)"
Cohesion: 0.07
Nodes (13): AuthController, CmsActivityLogController, CmsBannerController, CmsContactController, CmsUserController, ForgotPasswordController, ActivityLog, Banner (+5 more)

### Community 3 - "Documentation & Requirements (C3)"
Cohesion: 0.04
Nodes (51): 1.1 Inisialisasi Laravel, 1.2 Struktur Folder, 1.3 Aset Frontend, 2.1 Migration, 2.2 Model & Relasi, 2.3 Seeder, 3.1 Autentikasi Custom, 3.2 Middleware (+43 more)

### Community 4 - "CMS Management (C4)"
Cohesion: 0.07
Nodes (14): CmsAlbumController, CmsPhotoController, CmsVisitorStatisticController, GalleryPhotoController, GalleryAlbum, GalleryPhoto, WebsiteVisitor, VisitorStatisticService (+6 more)

### Community 5 - "Community 5"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 6 - "Documentation & Requirements (C6)"
Cohesion: 0.05
Nodes (41): 1. Filosofi Desain, 10. Responsif, 11. Panduan Penggunaan Bootstrap 5, 2. Color Palette, 3. Tipografi, 4. Spacing & Grid, 5. Komponen UI, 5.1 Tombol (Button) (+33 more)

### Community 7 - "CMS Management (C7)"
Cohesion: 0.08
Nodes (9): CmsPenanggungJawabController, CmsServiceCategoryController, CmsServiceController, ServiceController, PenanggungJawab, Service, ServiceCategory, PenanggungJawabSeeder (+1 more)

### Community 8 - "Community 8"
Cohesion: 0.07
Nodes (14): BannerSeeder, ContactSeeder, DatabaseSeeder, DocumentSeeder, GalleryAlbumSeeder, GalleryVideoSeeder, NewsCategorySeeder, OrganizationMemberSeeder (+6 more)

### Community 9 - "CMS Management (C9)"
Cohesion: 0.12
Nodes (7): CmsNewsCategoryController, CmsNewsController, NewsController, News, NewsCategory, NewsSeeder, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 10 - "Community 10"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 11 - "Community 11"
Cohesion: 0.10
Nodes (19): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+11 more)

### Community 12 - "CMS Management (C12)"
Cohesion: 0.23
Nodes (5): CmsAuthMiddleware, RoleMiddleware, TrackVisitor, Closure, Symfony\Component\HttpFoundation\Response

### Community 13 - "CMS Management (C13)"
Cohesion: 0.20
Nodes (4): CmsSettingController, ContactController, Setting, self

### Community 15 - "Community 15"
Cohesion: 0.27
Nodes (6): ResetPasswordMail, Illuminate\Bus\Queueable, Illuminate\Mail\Mailable, Illuminate\Mail\Mailables\Content, Illuminate\Mail\Mailables\Envelope, Illuminate\Queue\SerializesModels

### Community 16 - "Documentation & Requirements (C16)"
Cohesion: 0.22
Nodes (8): About Laravel, Code of Conduct, Contributing, Laravel Sponsors, Learning Laravel, License, Premium Partners, Security Vulnerabilities

### Community 18 - "Community 18"
Cohesion: 0.47
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

## Knowledge Gaps
- **255 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+250 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **15 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `CMS Management (C1)` to `CMS Management (C2)`, `CMS Management (C4)`, `CMS Management (C7)`, `CMS Management (C9)`, `CMS Management (C13)`, `CMS Management (C14)`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Why does `User` connect `CMS Management (C2)` to `CMS Management (C1)`, `CMS Management (C7)`, `Community 8`, `CMS Management (C9)`, `Community 15`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Why does `ActivityLog` connect `CMS Management (C2)` to `CMS Management (C1)`, `CMS Management (C4)`, `CMS Management (C7)`, `CMS Management (C9)`, `CMS Management (C13)`, `CMS Management (C14)`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Are the 52 inferred relationships involving `ActivityLog` (e.g. with `.login()` and `.logout()`) actually correct?**
  _`ActivityLog` has 52 INFERRED edges - model-reasoned connections that need verification._
- **Are the 12 inferred relationships involving `User` (e.g. with `.login()` and `.index()`) actually correct?**
  _`User` has 12 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _255 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Documentation & Requirements (C0)` be split into smaller, more focused modules?**
  _Cohesion score 0.023529411764705882 - nodes in this community are weakly interconnected._