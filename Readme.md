# Kirby Docs search workflow for Alfred 4

An ultra-fast Kirby Docs search workflow for Alfred 4

![Screenshot](screenshot.jpg)

## ⚠️ Prerequisites

Ensure you have PHP installed and available in terminal / shell. For macOS <12, PHP 7.3.X is already installed system-wide. For macOS 12+, the easiest way is to install [PHP](https://formulae.brew.sh/formula/php) via [Homebrew](https://brew.sh).

If you work with PHP installed in a different way (Docker, MAMP, ?), you should make the PHP executable installed that way available through `/usr/local/bin/php`, via symlink or other method.

## Installation

1. [Download the latest version](https://github.com/adamkiss/alfred-kirby-search/releases)
2. Install the workflow by double-clicking the `.alfredworkflow` file
3. You can add the workflow to a category, then click "Import" to finish importing. You'll now see the workflow listed in the left sidebar of your Workflows preferences pane.

## Usage

To search the [Kirby Docs](https://getkirby.com/docs/guide), just type `kd` followed by your search query.

```
kd <query>
```

## Acknowledgments

- Matt Clinton and their [Alfred Tailwind Docs search](https://github.com/clnt/alfred-tailwindcss-docs), which I used as a reference
- Bastian Allgeier for allowing me to piggyback on the Algolia search index already available on [getkirby.com](https://getkirby.com)

## Copyright / License

(c) 2021 Adam Kiss, licensed under [MIT License](https://github.com/adamkiss/alfred-kirby-search/blob/main/LICENSE).

![Search by Algolia](algolia.png)
