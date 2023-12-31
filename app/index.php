<!DOCTYPE html>
<html>

<head>
  <title>dtdi/slug.php</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body class="">
  <div class="container">

    <h1><strong>API Documentation <code>dtdi/slug.php</code></strong></h1>
    <h3 id="overview">Overview</h3>
    <p>This API allows you to slugify a given text based on specified parameters. The slugification process involves
      transforming a string into a URL-friendly format.</p>

    <h3>Try it yourself</h3>
    <div class="demo">
      <form id="slugifyForm">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="Interview Regarding Automated Process Improvement" required>
        <?php if ($_ENV['RESTRICT_API'] == 'true') : ?>
          <label for="apiKey">API Key:</label>
          <input type="text" id="apiKey" name="apiKey">
        <?php endif; ?>

        <label for="random">Random:</label>
        <input type="checkbox" id="random" name="random" <?php echo ($_ENV['DEFAULT_RANDOM'] == true) ? "checked" : "" ?>>

        <label for="hash">Hash:</label>
        <input type="checkbox" id="hash" name="hash" <?php echo ($_ENV['DEFAULT_HASH'] == true) ? "checked" : "" ?>>

        <label for="limit">Limit:</label>
        <input type="number" id="limit" name="limit" value="<?php echo ($_ENV['DEFAULT_LIMIT']) ?>" min="1" max="255">

        <label for="id">ID:</label>
        <input type="number" id="id" name="id">

        <label for="method">Method:</label>
        <select id="method" name="method">
          <option value="slug">Slug</option>
          <option value="studly">Studly</option>
          <option value="kebap">Kebap</option>
          <option value="snake">Snake</option>
          <option value="mail">Mail</option>

        </select>

        <button type="button" onclick="submitForm()">Submit</button>
      </form>
      <h4>API Response</h4>
      <div>
        <pre><code id="url"></code></pre>
      </div>

      <div id="output"></div>
      <pre><code id="raw"></code></pre>
    </div>
    <h3 id="request">Request</h3>
    <h4 id="parameters">Parameters</h4>
    <ul>
      <li>
        <code>name</code> (required): The input text to be slugified.
      </li>
      <?php if ($_ENV['RESTRICT_API'] == 'true') : ?>
        <li>
          <code>apiKey</code> (optional, default: empty string): An API key for authentication.
        </li>
      <?php endif; ?>
      <li>
        <code>random</code> (optional, default: false): A boolean flag indicating whether to append a random
        two-digit string to the slug.
      </li>
      <li>
        <code>limit</code> (optional, default: 255): An integer specifying the maximum length of the generated slug,
        including the random digit.
      </li>
      <li>
        <code>method</code> (optional, default: &#39;slug&#39;): The slugification method to be used. Available
        methods are &#39;slug&#39;, &#39;studly&#39;, &#39;kebap&#39;, &#39;snake&#39;.
      </li>
    </ul>
    <?php if ($_ENV['RESTRICT_API'] == 'true') : ?>
      <h3 id="authentication">Authentication</h3>
      <p>Ensure that the <code>apiKey</code> parameter is included in the request header with a valid API key. If the key
        is missing or incorrect, the API will return a 403 Forbidden response.</p>
    <?php endif; ?>
    <h3 id="slugification">Slugification</h3>
    <p>The API supports various slugification methods, including:</p>
    <ul>
      <li><code>slug</code>: Standard URL-friendly slug (default).</li>
      <li><code>studly</code>: StudlyCase slug.</li>
      <li><code>kebap</code>: KebapCase slug.</li>
      <li><code>snake</code>: Snake_case slug.</li>
      <li><code>mail</code>: Mail-Safe. Converts "Lastname, Firstname Secondname" to "firstname-secondname.lastname"</li>

    </ul>
    <h3 id="response">Response</h3>
    <p>The API returns a JSON response with the following information:</p>
    <ul>
      <li><code>name</code>: Original input text.</li>
      <li><code>name_clean</code>: Input text with extra whitespaces removed.</li>
      <li><code>method</code>: Slugification method used.</li>
      <li><code>slug</code>: The slugified text including the random and id part.</li>
      <li><code>random</code>: Boolean indicating whether a random string is appended.</li>
      <li><code>random_int</code>: The appended random int (if applicable).</li>
      <li><code>limit</code>: The specified maximum length for the slug.</li>
      <li><code>is_trimmed</code>: Boolean indicating whether the slug was trimmed to meet the length limit.</li>
      <li><code>is_hashed</code>: Boolean indicating whether the id or random part where hashed.</li>
      <li><code>id</code>: The id that was submitted via the request.</li>
      <li><code>hashed_id</code>: The hashed id if it was hashed.</li>
    </ul>


    <h3 id="example">Example</h3>

    <p>Request</p>
    <pre><code>GET <?php echo $request->url() ?>?name=Interview+Regarding+Automated+Process+Improvement&apiKey=&random=on&limit=120&id=&method=slug</code></pre>
    <p>Response</p>
    <pre><code>{
  "name": "Interview Regarding Automated Process Improvement",
  "name_clean": "Interview Regarding Automated Process Improvement",
  "method": "slug",
  "slug": "interview-regarding-automated-process-improvement-0-18",
  "random": true,
  "limit": 120,
  "is_trimmed": false,
  "is_hashed": false,
  "random_int": 18
}</code></pre>



    <script>
      var url = "";

      function updateOutput(data) {
        var outputElement = document.getElementById('output');
        var raw = document.getElementById('raw');
        var urlfield = document.getElementById('url');

        outputElement.innerHTML = '';
        raw.innerText = JSON.stringify(data, null, 2);
        urlfield.innerText = "GET " + url;

        // Create a list to display key-value pairs
        var list = document.createElement('ul');

        for (var key in data) {
          if (data.hasOwnProperty(key)) {
            var listItem = document.createElement('li');
            listItem.innerHTML = `<strong>${key}:</strong> ${data[key]}`;
            list.appendChild(listItem);
          }
        }

        // Append the list to the output element
        outputElement.appendChild(list);
      }

      function submitForm() {
        var form = document.getElementById('slugifyForm');
        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();
        var queryString = new URLSearchParams(formData).toString();
        url = "<?php echo $request->url() ?>?" + queryString;
        fetch(url)
          .then(response => response.json())
          .then(data => {
            // Update the HTML content with formatted JSON response
            updateOutput(data);
          })
          .catch(error => {
            var raw = document.getElementById('raw')

            raw.innerHTML = xhr.responseText;
          });
      }
    </script>

    <h3 id="error-handling">Error Handling</h3>
    <ul>
      <?php if ($_ENV['RESTRICT_API'] == 'true') : ?>
        <li>
          If the <code>apiKey</code> is missing or incorrect, the API will respond with a 403 Forbidden status and a
          message indicating the issue.
        </li>
      <?php endif; ?>
      <li>
        If an unsupported slugification method is provided, the API will default to the &#39;slug&#39; method.
      </li>
    </ul>
    <h3 id="note">Note</h3>
    <ul>
      <li>
        <p>Ensure that the request is made using the <code>GET</code> method.</p>
      </li>
    </ul>

    <div id="about-section">
      <h2>About <code>dtdi/slug.php</code></h2>
      <p>This website is a personal project:
        <a id="profile-link" href="https://github.com/dtdi/slug.php" target="_blank">dtdi/slug.php - Github</a>
      </p>
    </div>

    <h3>Psst. You're into business process management? </h3>
    <p>
      We have set up a collection for redesign patterns that can provide useful inspiration for process improvement.
    </p>
    <a href="https://dtdi.de/i.php?repo=slug" rel="nofollow"><img src="https://dtdi.de/ads/slug.png" width="419px" style="max-width: 100%;"></a>
  </div>

</body>

</html>