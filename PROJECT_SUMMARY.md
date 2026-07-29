# Project
Name: ReadFlow
Framework: Laravel 13
Environment: Docker + Nginx + MySQL
Pattern: MVC
Version: Sprint 5 Completed

# Architecture

ReadingMaterial
    └── hasMany ReadingGoal

ReadingGoal
    └── belongsTo ReadingMaterial
    └── hasMany ReadingSession

ReadingSession
    └── belongsTo ReadingGoal

ReadingNote
    └── belongsTo ReadingMaterial

ReadingSession NO LONGER belongs directly to ReadingMaterial.

Always access material through:

ReadingSession
→ ReadingGoal
→ ReadingMaterial

# Completed Features

Authentication

Email Verification

Reading Material CRUD

Category CRUD

Reading Goal CRUD

Reading Session CRUD

Reading Notes CRUD

Bookmarks

Dashboard

Progress Tracking

Session Duration Tracking

# Sprint History

Sprint 1
- Initial project setup
- Authentication
- Docker configuration

Sprint 2
- Reading Material CRUD
- Category CRUD

Sprint 3
- Reading Goal CRUD
- Reading Notes CRUD

Sprint 4
Major architecture refactor.

ReadingSession moved from ReadingMaterial to ReadingGoal.

New relationship:

ReadingMaterial
→ ReadingGoal
→ ReadingSession

Start Reading now only starts from Reading Goal.

Sprint 5

Completed.

Fixed Reading Session synchronization.

Goal progress now updates automatically using:

MAX(end_page)

instead of summing pages.

Fixed elapsed_seconds bug.

Timer now submits elapsed_seconds from frontend hidden input.

Finish Reading now stores:

- start_page
- end_page
- notes
- elapsed_seconds

Dashboard fixed.

DashboardController updated from:

with('readingMaterial')

to

with('readingGoal.readingMaterial')

after ReadingSession relationship refactor.

# Current Stable Features

Reading Material

Reading Goal

Reading Session

Reading Notes

Bookmarks

Dashboard

Progress calculation

Reading timer

Duration tracking

# Important Rules

Do not recreate ReadingSession → ReadingMaterial relationship.

Always use:

readingGoal.readingMaterial

Reading Goal owns Reading Sessions.

Reading Material is only a catalog.

# Next Sprint Backlog

Priority 1

Move Rating

Current:

Reading Notes has rating.

Target:

Reading Material should own rating.

Remove rating from Reading Notes.

Priority 2

Fix Bookmarks

Book cover image is missing on Bookmark page.

Use same cover loading logic as Reading Material page.

Priority 3

UI Enhancement

Improve color palette.

Improve buttons.

Improve cards.

Improve spacing.

Improve shadows.

Improve responsiveness.

Priority 4

Dashboard Analytics

Reading Statistics

Reading Time

Reading Streak

Charts

Weekly Activity

Monthly Activity

Top Categories

Reading Summary

# Development Workflow

Always work in small objectives.

One objective.

One implementation.

Test.

Fix.

Commit.

Then continue.

Never modify stable features unless explicitly requested.

Always preserve existing architecture.

# Current Status

Sprint 5 COMPLETE

Project Stable

Ready for Sprint 6