# UCSC Web Design System

[![Netlify Status](https://api.netlify.com/api/v1/badges/c7a6ff8b-b066-4ef5-8d94-2255be34e987/deploy-status)](https://app.netlify.com/sites/ucsc-design-system/deploys)

This is the official web design system for UC Santa Cruz. It has a Gulp-powered build system with these features:

- Handlebars HTML templates with Panini
- Sass compilation and prefixing
- JavaScript module bundling with webpack
- Built-in BrowserSync server
- For production builds:
  - CSS compression
  - JavaScript compression
  - Image compression

## Installation

To use this template, your computer needs:

- [NodeJS](https://nodejs.org/en/) (0.12 or greater)
- [Git](https://git-scm.com/)

```bash
cd PROJECT_DIRECTORY
npm install
```

Finally, run `npm start` to run Gulp. Your finished site will be created in a folder called `dist`, viewable at this URL:

```
http://localhost:8000
```

To create compressed, production-ready assets, run `npm run build`.
