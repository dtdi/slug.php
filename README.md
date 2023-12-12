
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

