# カスタムフィールイドとブロックバインディングのテスト

```
<!-- wp:image {
    "metadata":{
        "bindings":{
            "url":{
                "source":"core/post-meta",
                "args":{
                    "key":"cf-image-url"
                }
            }
        }
    }
} -->
<figure class="wp-block-image size-full"><img src="" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {
    "level":3,
    "metadata":{
        "bindings":{
            "content":{
                "source":"core/post-meta",
                "args":{
                    "key":"cf-image-title"
                }
            }
        }
    }
} -->
<h3 class="wp-block-heading"></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {
    "metadata":{
        "bindings":{
            "content":{
                "source":"core/post-meta",
                "args":{
                    "key":"cf-image-description"
                }
            }
        }
    }
} -->
<p></p>
<!-- /wp:paragraph -->
```

