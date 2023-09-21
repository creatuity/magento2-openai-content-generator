# Creatuity AI Content Generator Module for Magento

## Table of Contents
1. [Overview](#overview)
2. [Installation](#installation)
3. [User Guide](#user-guide)
    - [Configuration](#configuration)
    - [Short Description](#short-description)
    - [Description](#description)
    - [Meta Tags](#meta-tags)
    - [Mass Actions](#mass-actions)
4. [Links](#links)

## Overview
The Creatuity AI Content Generator module for Magento allows for the automated generation of descriptions and metadata for products based on their attributes. By interfacing with OpenAI, product attributes can be utilized to produce compelling content with ease.

This module consists of:
- [Core Module](https://github.com/creatuity/magento2-ai-content-generator-core)
- [Mass Actions Module](https://github.com/creatuity/magento2-ai-content-generator-mass-actions)
- [OpenAI's Integration Module](https://github.com/creatuity/magento2-openai-content-generator)

The core is designed with potential future integrations in mind, allowing for easy integration with other AI services.

## Installation
```bash
composer require creatuity/magento2-openai-content-generator
bin/magento s:up
```
If you have problem with instalation, add the following repositoreis to your composer.json file

```json
{
    "type": "vcs",
    "url": "git@github.com:creatuity/magento2-openai-content-generator.git"
},
{
    "type": "vcs",
    "url": "git@github.com:creatuity/magento2-ai-content-generator-core.git"
},
{
    "type": "vcs",
    "url": "git@github.com:creatuity/magento2-ai-content-generator-mass-actions.git"
}
```

## User Guide

### Configuration
1. Navigate to `Stores → Configuration → Creatuity → AI Content`.
2. Configure the fields as per your requirement:
    ![image](https://github.com/creatuity/magento2-openai-content-generator/assets/1653168/5720949f-169b-4f14-af8f-878608835752)


    | Configuration Field    | Description | Default Value |
    |------------------------|-------------|---------------|
    | Enabled                | Enable/Disable the module | No |
    | AI Provider            | Choose AI Provider (Only OpenAI currently) | OpenAI |
    | Description Attributes | Default attributes for description generation | Product Name, Size, Color |
    | Meta-tags Attributes   | Default attributes for meta-tag generation | Product Name, Description |
    | OpenAI API Key         | Your OpenAI API Token | - |
    | Model Name             | OpenAI model for requests. Model determines cost | gpt-3.5-turbo |


Once the module is configured and enabled, you can go to product configuration.

**NOTE:** You can’t generate any data using the module until product is created
![image](https://github.com/creatuity/magento2-openai-content-generator/assets/1653168/9a7d3474-eb98-45a8-a344-3eb5329db1ea)

When you create and configure product **(it is strongly recommended to fully configure product attributes first before generating description and meta-tags)** you can go to a proper section where you are able now to click **Generate With AI** button 

The button opens a modal window where you can optionally configure some prompt settings which will be sent to AI model

Example:

![image](https://github.com/creatuity/magento2-openai-content-generator/assets/1653168/ff906f51-3f9c-4f0b-8205-1fae72097447)

Product Attributes values are by default the same as the once selected in configuration. You can change here these selected by default attributes by selecting other attributes that you want to use to generate short description and description for this particular product

### Short Description
- In the product configuration, navigate to the Content section.
- Click on "Generate With AI" to open a modal.
- Here, you can customize the product attributes used for generating content.
- Generated content appears in the Short Description's textarea.
- To regenerate content, adjust settings and click "Generate" again.
- Once satisfied, click "Apply" to move the generated content to the product's Short Description field.

### Description
This section works similarly to the Short Description section. If you have Page Builder enabled, you'll need to manually copy the generated description and paste it in the desired field as auto-fill is unsupported.

### Meta Tags
1. Once the product descriptions are finalized, navigate to the `Search Engine Optimization` section.
2. Click "Generate With AI" to open a modal.
3. You can modify the default attributes used for meta-tag generation.
4. Click "Generate" to get three meta-tag suggestions.
5. Choose your preferred meta-tag and click "Apply".
6. Remember to save the product.

### Mass Actions
For bulk content generation:
1. On the product grid, select the products you want.
2. Choose either:
    - Generate With AI → Description
    - Generate With AI → Meta-Tags
3. On the redirected page, generate and save content for products individually using "Generate One By One".
4. Use "Skip" to bypass a product or "Confirm and Continue" to proceed to the next product.
5. You can halt the process anytime. Pending products will always be displayed in the admin notifications bar.

## Links
- [Core Module Repository](https://github.com/creatuity/magento2-ai-content-generator-core)
- [Mass Actions Module](https://github.com/creatuity/magento2-ai-content-generator-mass-actions)
- [OpenAI Integration Module Repository](https://github.com/creatuity/magento2-openai-content-generator)

---

For any issues or feedback, please raise a ticket in the respective repository.
