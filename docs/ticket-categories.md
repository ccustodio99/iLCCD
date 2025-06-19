# 🗂 Ticket Categories – LCCD Integrated Information System

This document provides a quick reference to the ticket category hierarchy. Each parent category has several subcategories used when filing a ticket. The database uses a `parent_id` field to maintain these relationships.

## Category Hierarchy

- **Computers & Devices**
  - Desktops & Laptops
  - Mobile Devices (Tablets, Chromebooks, Smartphones)
  - Peripherals (Keyboards, Mice, Monitors, External Drives)
  - Classroom AV (Projectors, Interactive Whiteboards, Smart Displays)
  - Printers & Scanners
- **Software & Apps**
  - Operating Systems (Install, Upgrade, Patching)
  - Applications (Installation, Licensing, Crashes)
  - Third-Party Tools (Adobe, Zoom, etc.)
  - Performance Issues (Slow Boot, Hangups)
- **Network & Access**
  - Wi-Fi / Wired Access
  - VPN & Remote Access
  - Network Outages
- **User Accounts & Access**
  - Password Resets / Unlocks
  - New Account Onboarding
  - Permissions & Role Changes
- **Printing & Scanning**
  - Print Queue Errors
  - Print Quality (Smudges, Streaks)
  - Scanner Setup & Integration
- **Procurement & Inventory**
  - New Hardware/Software Requests
- **Facilities & Maintenance**
  - Preventative Maintenance
  - Repairs & Emergencies
- **Security & Safety**
  - Malware / Virus Incidents
  - Phishing Reports
- **Training & Support**
  - Workshops & Tutorials
  - User Guides & Documentation
- **Feedback & Improvement**
  - Service Feedback
  - Feature Requests
  - Usability Suggestions
- **Other / General Inquiry**
  - General Inquiry

## Deleting Categories

When removing a parent ticket category from the system, the application checks
if it has child categories using `$ticketCategory->children()->exists()`.
If any are found, those subcategories are soft deleted at the same time so
that no orphaned records remain.
