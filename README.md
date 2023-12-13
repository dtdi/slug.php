
# slug.php

A little helper API that generates URL-safe Slugs for URLs. Especially useful when working within Microsoft Power Automate which does not provide proper slugging-functionality. 

## API Reference

#### Create Slug

```http
  GET /?name=Your title
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name`   | `string` | **Required**. The input text to be slugified. |
| `random`   | `string` | A boolean flag indicating whether to append a random two-digit string to the slug. |
| `apiKey` | `string` | An API key for authentication. |
| `id` | `number` | An integer specifying a mandatory id that must be added to the end of the slug. |
| `hash` | `number` | A boolean flag indicating whether to hash the random code or the id. |
| `limit` | `number` | An integer specifying the maximum length of the generated slug, including the random digit. |
| `method` | `number` | The slugification method to be used. Available methods are 'slug', 'studly', 'kebap', 'snake'. |


```json
{
  "name":"Interview Regarding Automated Process Improvement",
  "name_clean":"Interview Regarding Automated Process Improvement",
  "method":"snake",
  "slug":"interview_regarding_automated_process_improvement89",
  "random":true,
  "random_str":" 89",
  "limit":"80",
  "is_trimmed":false
}
```

## Authors

- [@dtdi](https://www.github.com/dtdi)


### Psst. You're into business process management? 

We have set up a collection for redesign patterns that can provide useful inspiration for process improvement. 

[<img src="https://dtdi.de/ads/slug.png" width="419px" />](https://dtdi.de/i.php?repo=slug)
