# Adventure Time API

The Adventure Time API is based on the television show Adventure Time. You will access to data about of characters, kingdoms and episodes.

## Getting Started

Checkout the [documentation](https://adventure-time-api-docs.vercel.app) to get started

## Build with:

-   Laravel 10.x
-   MySQL

# How to contribute with DATA

If you want to contribute with data like Characters, Episodes or Kingdoms, here's a guide.

### 1. Data

Data is seeded with the json files **/database/data/** you can add or edit information from here.

### 2. Images

Images are located to _/public/assets/images_. Every item has two files with next format:

1.  item-name.webp
1.  item-name-**thumbnail**.webp

You can optimize the images and then reference them in the json files.

#### Image path

Image paths have to start with **/assets/images/{collection}**

**Collection** can be characters, kingdoms or episodes

## Example

```json
{
        "slug": "candy-kingdom",
        "name": "Candy Kingdom",
        "description": "The Candy Kingdom si...",
        "thumbnail": "/assets/images/kingdoms/candy-kingdom-thumbnail.webp",
        "image": "/assets/images/kingdoms/candy-kingdom.webp"
    }
```

## Finally
I'll check the pull requests. if everything is okay. I'll merge it. Thanks <3