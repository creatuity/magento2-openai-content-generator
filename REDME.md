# Magento 2 Creatuity OpenAI Content Generator Module
The Creatuity AI Content Generator module provides a powerful tool that generates descriptions and metadata for your products based on their attributes.

## What is it?
The module facilitates the automated creation of product descriptions and metadata via the OpenAI API. With a simple click on the "Generate" button, users can send a request to OpenAI using selected product attributes. The module consists of two key submodules:
- [The core module](https://github.com/creatuity/magento2-ai-content-generator-core)
- [OpenAI’s integration module](https://github.com/creatuity/magento2-openai-content-generator)


## Installation
To install the module, run the following commands:
```
composer require creatuity/magento2-openai-content-generator
bin/magento s:up
```

## User Guide
After successful installation, the module needs to be configured. Go to `Stores → Configuration → Creatuity → AI Content`.

The configuration fields are as follows:

| Configuration Field   | Description                                                                                                      | Default                            |
|-----------------------|------------------------------------------------------------------------------------------------------------------|------------------------------------|
| Enabled               | Enable or disable the module                                                                                      | No                                 |
| AI Provider           | Select the AI provider (currently only OpenAI available)                                                          | OpenAI                             |
| Description Attributes| Defines which attributes are used by default to generate product descriptions                                     | Product Name, Size, Color          |
| Meta-tags Attributes  | Determines which attributes are used to generate meta-tags for product PDP webpage                                | Product Name, Description          |
| API Key               | The API token generated in your OpenAI Account                                                                   | -                                  |
| Model Name            | The chosen OpenAI AI model to handle the requests. This determines the cost of using the module - [OpenAI pricing](https://openai.com/pricing) | gpt-3.5-turbo                      |

Once configured and enabled, you can navigate to the product configuration section. It is important to note that no data can be generated using the module until a product has been created.

**IMPORTANT:** Full configuration of product attributes is strongly recommended before generating descriptions and meta-tags.

### Generating Product Descriptions and Meta-Tags
1. After creating and configuring a product, navigate to the 'Content' section, and click on the 'Generate With AI' button.

2. The 'Generate With AI' button opens a modal window where you can optionally configure some prompt settings that will be sent to the AI model.

3. The generated response from the AI API will appear in the 'Short Description's textarea. Here you can also set a maximum text length. However, the exact length of the generated text is not guaranteed.

4. Once you're happy with the generated description, click 'Apply' to automatically transfer the text into the 'Short Description' field in the 'Content' section.

5. Similarly, you can generate a 'Description'. The only difference is that if 'Page Builder' is enabled, you will need to manually copy the generated text and paste it in the 'Description' field as Page Builder fields do not support automatic filling of AI-generated text.

6. When you're done with descriptions, don't forget to save the product.

### Generating Meta Tags
1. Once product descriptions are ready, navigate to the 'Search Engine Optimization' section.

2. Click on 'Generate With AI'. Here you can again choose to change the default attributes used to generate the meta-tags. By default, 'Product Name' and 'Description' are selected.

3. After clicking on 'Generate', you will receive three propositions of corresponding meta-tag types.

4. Pick one and click the 'Apply' button beneath it to transfer it to the proper field in the 'Search Engine Optimization' section.

5. Don't forget to save the product after finishing the meta tag generation.

Happy Product Configuration!