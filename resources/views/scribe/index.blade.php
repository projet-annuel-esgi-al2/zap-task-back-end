<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "https://e3be6436e560.ngrok-free.app";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.2.1.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.2.1.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authentication" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authentication">
                    <a href="#authentication">Authentication</a>
                </li>
                                    <ul id="tocify-subheader-authentication" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="authentication-POSTapi-register">
                                <a href="#authentication-POSTapi-register">Register a new user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-me">
                                <a href="#authentication-POSTapi-me">Login a previously registered user</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-PUTapi-workflows--workflow--">
                                <a href="#endpoints-PUTapi-workflows--workflow--">PUT api/workflows/{workflow?}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-actions-execute--action_id-">
                                <a href="#endpoints-POSTapi-actions-execute--action_id-">POST api/actions/execute/{action_id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-workflows-trigger">
                                <a href="#endpoints-POSTapi-workflows-trigger">POST api/workflows/trigger</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-services-oauth" class="tocify-header">
                <li class="tocify-item level-1" data-unique="services-oauth">
                    <a href="#services-oauth">Services OAuth</a>
                </li>
                                    <ul id="tocify-subheader-services-oauth" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="services-oauth-GETapi-subscriptions--serviceIdentifier-">
                                <a href="#services-oauth-GETapi-subscriptions--serviceIdentifier-">Check If User Is Subscribed To Service</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="services-oauth-GETapi--serviceIdentifier--redirect">
                                <a href="#services-oauth-GETapi--serviceIdentifier--redirect">Fetch Service's OAuth Consent Screen</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-services-and-actions" class="tocify-header">
                <li class="tocify-item level-1" data-unique="services-and-actions">
                    <a href="#services-and-actions">Services and Actions</a>
                </li>
                                    <ul id="tocify-subheader-services-and-actions" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="services-and-actions-GETapi--serviceIdentifier--actions">
                                <a href="#services-and-actions-GETapi--serviceIdentifier--actions">Fetch actions for a specified Service</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="services-and-actions-GETapi-services">
                                <a href="#services-and-actions-GETapi-services">Fetch all available services</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-workflows" class="tocify-header">
                <li class="tocify-item level-1" data-unique="workflows">
                    <a href="#workflows">Workflows</a>
                </li>
                                    <ul id="tocify-subheader-workflows" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="workflows-GETapi-workflows">
                                <a href="#workflows-GETapi-workflows">Fetch User's Workflows</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="workflows-GETapi-workflows--workflow_id-">
                                <a href="#workflows-GETapi-workflows--workflow_id-">Fetch A Workflow And Its Actions If Present</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="workflows-POSTapi-workflows-deploy--workflow_id-">
                                <a href="#workflows-POSTapi-workflows-deploy--workflow_id-">Deploy A Workflow</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="workflows-DELETEapi-workflows--workflow_id-">
                                <a href="#workflows-DELETEapi-workflows--workflow_id-">Delete A Workflow</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: July 11, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>https://e3be6436e560.ngrok-free.app</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        

        <h1 id="authentication">Authentication</h1>

    

                                <h2 id="authentication-POSTapi-register">Register a new user</h2>

<p>
</p>



<span id="example-requests-POSTapi-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://e3be6436e560.ngrok-free.app/api/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"email\": \"gbailey@example.net\",
    \"password\": \"architecto\",
    \"password_confirmation\": \"architecto\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "email": "gbailey@example.net",
    "password": "architecto",
    "password_confirmation": "architecto"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-register">
</span>
<span id="execution-results-POSTapi-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-register" data-method="POST"
      data-path="api/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-register"
                    onclick="tryItOut('POSTapi-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-register"
                    onclick="cancelTryOut('POSTapi-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-register"
               value="b"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-register"
               value="gbailey@example.net"
               data-component="body">
    <br>
<p>Example: <code>gbailey@example.net</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-register"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-register"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-me">Login a previously registered user</h2>

<p>
</p>



<span id="example-requests-POSTapi-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://e3be6436e560.ngrok-free.app/api/me" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"gbailey@example.net\",
    \"password\": \"architecto\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/me"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "gbailey@example.net",
    "password": "architecto"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-me">
</span>
<span id="execution-results-POSTapi-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-me" data-method="POST"
      data-path="api/me"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-me"
                    onclick="tryItOut('POSTapi-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-me"
                    onclick="cancelTryOut('POSTapi-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-me"
               value="gbailey@example.net"
               data-component="body">
    <br>
<p>Must be a valid email address. Example: <code>gbailey@example.net</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-me"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
        </form>

                <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-PUTapi-workflows--workflow--">PUT api/workflows/{workflow?}</h2>

<p>
</p>



<span id="example-requests-PUTapi-workflows--workflow--">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "https://e3be6436e560.ngrok-free.app/api/workflows/ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"actions\": [
        {
            \"execution_order\": \"architecto\"
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/workflows/ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "actions": [
        {
            "execution_order": "architecto"
        }
    ]
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-workflows--workflow--">
</span>
<span id="execution-results-PUTapi-workflows--workflow--" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-workflows--workflow--"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-workflows--workflow--"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-workflows--workflow--" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-workflows--workflow--">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-workflows--workflow--" data-method="PUT"
      data-path="api/workflows/{workflow?}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-workflows--workflow--', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-workflows--workflow--"
                    onclick="tryItOut('PUTapi-workflows--workflow--');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-workflows--workflow--"
                    onclick="cancelTryOut('PUTapi-workflows--workflow--');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-workflows--workflow--"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/workflows/{workflow?}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-workflows--workflow--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-workflows--workflow--"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>workflow</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="workflow"                data-endpoint="PUTapi-workflows--workflow--"
               value="ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79"
               data-component="url">
    <br>
<p>Example: <code>ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-workflows--workflow--"
               value="b"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>actions</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
<i>optional</i> &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>execution_order</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="actions.0.execution_order"                data-endpoint="PUTapi-workflows--workflow--"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>parameters</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="actions.0.parameters"                data-endpoint="PUTapi-workflows--workflow--"
               value=""
               data-component="body">
    <br>

                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-actions-execute--action_id-">POST api/actions/execute/{action_id}</h2>

<p>
</p>



<span id="example-requests-POSTapi-actions-execute--action_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://e3be6436e560.ngrok-free.app/api/actions/execute/66dcff0c-77df-472a-92a8-a67b8474b7ed" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/actions/execute/66dcff0c-77df-472a-92a8-a67b8474b7ed"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-actions-execute--action_id-">
</span>
<span id="execution-results-POSTapi-actions-execute--action_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-actions-execute--action_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-actions-execute--action_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-actions-execute--action_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-actions-execute--action_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-actions-execute--action_id-" data-method="POST"
      data-path="api/actions/execute/{action_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-actions-execute--action_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-actions-execute--action_id-"
                    onclick="tryItOut('POSTapi-actions-execute--action_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-actions-execute--action_id-"
                    onclick="cancelTryOut('POSTapi-actions-execute--action_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-actions-execute--action_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/actions/execute/{action_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-actions-execute--action_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-actions-execute--action_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>action_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="action_id"                data-endpoint="POSTapi-actions-execute--action_id-"
               value="66dcff0c-77df-472a-92a8-a67b8474b7ed"
               data-component="url">
    <br>
<p>The ID of the action. Example: <code>66dcff0c-77df-472a-92a8-a67b8474b7ed</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-workflows-trigger">POST api/workflows/trigger</h2>

<p>
</p>



<span id="example-requests-POSTapi-workflows-trigger">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://e3be6436e560.ngrok-free.app/api/workflows/trigger" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/workflows/trigger"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-workflows-trigger">
</span>
<span id="execution-results-POSTapi-workflows-trigger" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-workflows-trigger"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-workflows-trigger"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-workflows-trigger" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-workflows-trigger">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-workflows-trigger" data-method="POST"
      data-path="api/workflows/trigger"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-workflows-trigger', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-workflows-trigger"
                    onclick="tryItOut('POSTapi-workflows-trigger');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-workflows-trigger"
                    onclick="cancelTryOut('POSTapi-workflows-trigger');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-workflows-trigger"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/workflows/trigger</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-workflows-trigger"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-workflows-trigger"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="services-oauth">Services OAuth</h1>

    

                                <h2 id="services-oauth-GETapi-subscriptions--serviceIdentifier-">Check If User Is Subscribed To Service</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-subscriptions--serviceIdentifier-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://e3be6436e560.ngrok-free.app/api/subscriptions/google-calendar" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/subscriptions/google-calendar"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-subscriptions--serviceIdentifier-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;User is not subscribed to this service.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-subscriptions--serviceIdentifier-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-subscriptions--serviceIdentifier-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-subscriptions--serviceIdentifier-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-subscriptions--serviceIdentifier-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-subscriptions--serviceIdentifier-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-subscriptions--serviceIdentifier-" data-method="GET"
      data-path="api/subscriptions/{serviceIdentifier}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-subscriptions--serviceIdentifier-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-subscriptions--serviceIdentifier-"
                    onclick="tryItOut('GETapi-subscriptions--serviceIdentifier-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-subscriptions--serviceIdentifier-"
                    onclick="cancelTryOut('GETapi-subscriptions--serviceIdentifier-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-subscriptions--serviceIdentifier-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/subscriptions/{serviceIdentifier}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-subscriptions--serviceIdentifier-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-subscriptions--serviceIdentifier-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>serviceIdentifier</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="serviceIdentifier"                data-endpoint="GETapi-subscriptions--serviceIdentifier-"
               value="google-calendar"
               data-component="url">
    <br>
<p>Example: <code>google-calendar</code></p>
            </div>
                    </form>

                    <h2 id="services-oauth-GETapi--serviceIdentifier--redirect">Fetch Service&#039;s OAuth Consent Screen</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi--serviceIdentifier--redirect">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://e3be6436e560.ngrok-free.app/api/google-calendar/redirect" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/google-calendar/redirect"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi--serviceIdentifier--redirect">
            <blockquote>
            <p>Example response (302):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
vary: Origin
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">&quot;No access token specified&quot;</code>
 </pre>
    </span>
<span id="execution-results-GETapi--serviceIdentifier--redirect" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi--serviceIdentifier--redirect"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi--serviceIdentifier--redirect"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi--serviceIdentifier--redirect" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi--serviceIdentifier--redirect">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi--serviceIdentifier--redirect" data-method="GET"
      data-path="api/{serviceIdentifier}/redirect"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi--serviceIdentifier--redirect', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi--serviceIdentifier--redirect"
                    onclick="tryItOut('GETapi--serviceIdentifier--redirect');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi--serviceIdentifier--redirect"
                    onclick="cancelTryOut('GETapi--serviceIdentifier--redirect');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi--serviceIdentifier--redirect"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/{serviceIdentifier}/redirect</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi--serviceIdentifier--redirect"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi--serviceIdentifier--redirect"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>serviceIdentifier</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="serviceIdentifier"                data-endpoint="GETapi--serviceIdentifier--redirect"
               value="google-calendar"
               data-component="url">
    <br>
<p>Example: <code>google-calendar</code></p>
            </div>
                    </form>

                <h1 id="services-and-actions">Services and Actions</h1>

    

                                <h2 id="services-and-actions-GETapi--serviceIdentifier--actions">Fetch actions for a specified Service</h2>

<p>
</p>



<span id="example-requests-GETapi--serviceIdentifier--actions">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://e3be6436e560.ngrok-free.app/api/google-calendar/actions" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/google-calendar/actions"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi--serviceIdentifier--actions">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
vary: Origin
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">&quot;No access token specified&quot;</code>
 </pre>
    </span>
<span id="execution-results-GETapi--serviceIdentifier--actions" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi--serviceIdentifier--actions"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi--serviceIdentifier--actions"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi--serviceIdentifier--actions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi--serviceIdentifier--actions">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi--serviceIdentifier--actions" data-method="GET"
      data-path="api/{serviceIdentifier}/actions"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi--serviceIdentifier--actions', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi--serviceIdentifier--actions"
                    onclick="tryItOut('GETapi--serviceIdentifier--actions');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi--serviceIdentifier--actions"
                    onclick="cancelTryOut('GETapi--serviceIdentifier--actions');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi--serviceIdentifier--actions"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/{serviceIdentifier}/actions</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi--serviceIdentifier--actions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi--serviceIdentifier--actions"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>serviceIdentifier</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="serviceIdentifier"                data-endpoint="GETapi--serviceIdentifier--actions"
               value="google-calendar"
               data-component="url">
    <br>
<p>Example: <code>google-calendar</code></p>
            </div>
                    </form>

                    <h2 id="services-and-actions-GETapi-services">Fetch all available services</h2>

<p>
</p>



<span id="example-requests-GETapi-services">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://e3be6436e560.ngrok-free.app/api/services" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/services"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-services">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
vary: Origin
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">&quot;No access token specified&quot;</code>
 </pre>
    </span>
<span id="execution-results-GETapi-services" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-services"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-services"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-services" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-services">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-services" data-method="GET"
      data-path="api/services"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-services', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-services"
                    onclick="tryItOut('GETapi-services');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-services"
                    onclick="cancelTryOut('GETapi-services');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-services"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/services</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-services"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-services"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="workflows">Workflows</h1>

    

                                <h2 id="workflows-GETapi-workflows">Fetch User&#039;s Workflows</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-workflows">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://e3be6436e560.ngrok-free.app/api/workflows" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/workflows"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-workflows">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;a4855dc5-0acb-33c3-b921-f4291f719ca0&quot;,
            &quot;name&quot;: &quot;Macey Rempel PhD&quot;,
            &quot;status&quot;: &quot;draft&quot;,
            &quot;saved_at&quot;: &quot;2019-06-17T02:38:43.000000Z&quot;,
            &quot;deployed_at&quot;: &quot;2020-08-18T09:47:53.000000Z&quot;
        },
        {
            &quot;id&quot;: &quot;0733da06-3f67-3b0d-b923-684c336397e5&quot;,
            &quot;name&quot;: &quot;Vito Bradtke&quot;,
            &quot;status&quot;: &quot;tested&quot;,
            &quot;saved_at&quot;: &quot;1976-06-15T13:59:48.000000Z&quot;,
            &quot;deployed_at&quot;: &quot;1978-02-21T03:10:37.000000Z&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-workflows" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-workflows"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-workflows"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-workflows" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-workflows">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-workflows" data-method="GET"
      data-path="api/workflows"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-workflows', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-workflows"
                    onclick="tryItOut('GETapi-workflows');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-workflows"
                    onclick="cancelTryOut('GETapi-workflows');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-workflows"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/workflows</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-workflows"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-workflows"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="workflows-GETapi-workflows--workflow_id-">Fetch A Workflow And Its Actions If Present</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-workflows--workflow_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://e3be6436e560.ngrok-free.app/api/workflows/ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/workflows/ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-workflows--workflow_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;3c59ffd2-2549-3665-95f3-35695ca34623&quot;,
        &quot;name&quot;: &quot;Dr. Lennie Klocko&quot;,
        &quot;status&quot;: &quot;saved&quot;,
        &quot;actions&quot;: [
            {
                &quot;id&quot;: &quot;ed9f5ece-c81b-41d1-ba5b-055d6b99fbd1&quot;,
                &quot;workflow_id&quot;: &quot;3c59ffd2-2549-3665-95f3-35695ca34623&quot;,
                &quot;service&quot;: {
                    &quot;identifier&quot;: &quot;google-mail&quot;,
                    &quot;name&quot;: &quot;Google Mail&quot;
                },
                &quot;service_action_id&quot;: &quot;25fb5892-f0fb-493e-ad8c-a164322abd9d&quot;,
                &quot;identifier&quot;: &quot;google-calendar-event-created&quot;,
                &quot;name&quot;: &quot;Jeremie Lindgren&quot;,
                &quot;type&quot;: &quot;action&quot;,
                &quot;status&quot;: &quot;draft&quot;,
                &quot;execution_order&quot;: 0,
                &quot;url&quot;: &quot;https://www.barton.com/iusto-facere-possimus-aliquam-consequatur&quot;,
                &quot;parameters&quot;: [],
                &quot;last_executed_at&quot;: &quot;2014-05-01T00:05:13.000000Z&quot;
            },
            {
                &quot;id&quot;: &quot;2f43330e-3c0a-40dc-94a0-375e89c36285&quot;,
                &quot;workflow_id&quot;: &quot;3c59ffd2-2549-3665-95f3-35695ca34623&quot;,
                &quot;service&quot;: {
                    &quot;identifier&quot;: &quot;google-mail&quot;,
                    &quot;name&quot;: &quot;Google Mail&quot;
                },
                &quot;service_action_id&quot;: &quot;dfea7571-f348-442e-8c5d-6d686cda72db&quot;,
                &quot;identifier&quot;: &quot;google-calendar-event-updated&quot;,
                &quot;name&quot;: &quot;Trinity Swaniawski&quot;,
                &quot;type&quot;: &quot;trigger&quot;,
                &quot;status&quot;: &quot;draft&quot;,
                &quot;execution_order&quot;: 1,
                &quot;url&quot;: &quot;https://halvorson.com/neque-sit-sunt-nihil-accusantium-odit-ut-perspiciatis.html&quot;,
                &quot;parameters&quot;: [],
                &quot;last_executed_at&quot;: &quot;1979-11-08T09:14:24.000000Z&quot;
            },
            {
                &quot;id&quot;: &quot;e5899de4-276d-423a-9d52-51a13f96642e&quot;,
                &quot;workflow_id&quot;: &quot;3c59ffd2-2549-3665-95f3-35695ca34623&quot;,
                &quot;service&quot;: {
                    &quot;identifier&quot;: &quot;google-mail&quot;,
                    &quot;name&quot;: &quot;Google Mail&quot;
                },
                &quot;service_action_id&quot;: &quot;44a1d606-2723-438e-8946-d595a38ef9ea&quot;,
                &quot;identifier&quot;: &quot;google-calendar-event-updated&quot;,
                &quot;name&quot;: &quot;Cody Welch&quot;,
                &quot;type&quot;: &quot;action&quot;,
                &quot;status&quot;: &quot;draft&quot;,
                &quot;execution_order&quot;: 7,
                &quot;url&quot;: &quot;http://www.rowe.com/&quot;,
                &quot;parameters&quot;: [],
                &quot;last_executed_at&quot;: &quot;1999-02-20T07:51:55.000000Z&quot;
            },
            {
                &quot;id&quot;: &quot;d54b634a-58e7-4521-b66b-7b17dc4df821&quot;,
                &quot;workflow_id&quot;: &quot;3c59ffd2-2549-3665-95f3-35695ca34623&quot;,
                &quot;service&quot;: {
                    &quot;identifier&quot;: &quot;google-mail&quot;,
                    &quot;name&quot;: &quot;Google Mail&quot;
                },
                &quot;service_action_id&quot;: &quot;7f75f8ce-774b-4f3a-974b-885d90be6280&quot;,
                &quot;identifier&quot;: &quot;google-calendar-create-event&quot;,
                &quot;name&quot;: &quot;Mara Bode&quot;,
                &quot;type&quot;: &quot;trigger&quot;,
                &quot;status&quot;: &quot;error&quot;,
                &quot;execution_order&quot;: 8,
                &quot;url&quot;: &quot;http://www.brekke.com/aliquam-qui-molestiae-voluptatem-est-voluptatem-molestiae-magnam-blanditiis&quot;,
                &quot;parameters&quot;: [],
                &quot;last_executed_at&quot;: &quot;1971-06-19T12:34:23.000000Z&quot;
            }
        ],
        &quot;saved_at&quot;: &quot;1973-12-08T16:16:12.000000Z&quot;,
        &quot;deployed_at&quot;: &quot;1997-10-15T16:36:41.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-workflows--workflow_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-workflows--workflow_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-workflows--workflow_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-workflows--workflow_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-workflows--workflow_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-workflows--workflow_id-" data-method="GET"
      data-path="api/workflows/{workflow_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-workflows--workflow_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-workflows--workflow_id-"
                    onclick="tryItOut('GETapi-workflows--workflow_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-workflows--workflow_id-"
                    onclick="cancelTryOut('GETapi-workflows--workflow_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-workflows--workflow_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/workflows/{workflow_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-workflows--workflow_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-workflows--workflow_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>workflow_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="workflow_id"                data-endpoint="GETapi-workflows--workflow_id-"
               value="ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79"
               data-component="url">
    <br>
<p>The ID of the workflow. Example: <code>ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79</code></p>
            </div>
                    </form>

                    <h2 id="workflows-POSTapi-workflows-deploy--workflow_id-">Deploy A Workflow</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-workflows-deploy--workflow_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://e3be6436e560.ngrok-free.app/api/workflows/deploy/ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/workflows/deploy/ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-workflows-deploy--workflow_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;6b72fe4a-5b40-307c-bc24-f79acf9a1bb9&quot;,
            &quot;execution_status&quot;: &quot;error&quot;,
            &quot;response_http_code&quot;: &quot;401&quot;,
            &quot;execution_order&quot;: 6,
            &quot;exception&quot;: &quot;Fugiat sunt nihil accusantium harum mollitia. Deserunt aut ab provident perspiciatis quo omnis nostrum. Adipisci quidem nostrum qui commodi incidunt iure.&quot;,
            &quot;url&quot;: &quot;http://www.considine.com/eum-doloremque-id-aut-libero-aliquam-veniam-corporis&quot;,
            &quot;parameters&quot;: [],
            &quot;executed_at&quot;: &quot;2023-08-25T13:50:02.000000Z&quot;
        },
        {
            &quot;id&quot;: &quot;72f27403-46dc-3596-a1cf-ea85c85b2476&quot;,
            &quot;execution_status&quot;: &quot;success&quot;,
            &quot;response_http_code&quot;: &quot;200&quot;,
            &quot;execution_order&quot;: 0,
            &quot;exception&quot;: &quot;Consectetur assumenda eaque neque sit. Nihil accusantium odit ut perspiciatis. Dolorem aut quis ut dolores omnis.&quot;,
            &quot;url&quot;: &quot;http://hill.com/aperiam-quis-est-nulla-vel-voluptas-aliquam-qui.html&quot;,
            &quot;parameters&quot;: [],
            &quot;executed_at&quot;: &quot;2020-07-09T01:02:17.000000Z&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-workflows-deploy--workflow_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-workflows-deploy--workflow_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-workflows-deploy--workflow_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-workflows-deploy--workflow_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-workflows-deploy--workflow_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-workflows-deploy--workflow_id-" data-method="POST"
      data-path="api/workflows/deploy/{workflow_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-workflows-deploy--workflow_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-workflows-deploy--workflow_id-"
                    onclick="tryItOut('POSTapi-workflows-deploy--workflow_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-workflows-deploy--workflow_id-"
                    onclick="cancelTryOut('POSTapi-workflows-deploy--workflow_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-workflows-deploy--workflow_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/workflows/deploy/{workflow_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-workflows-deploy--workflow_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-workflows-deploy--workflow_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>workflow_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="workflow_id"                data-endpoint="POSTapi-workflows-deploy--workflow_id-"
               value="ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79"
               data-component="url">
    <br>
<p>The ID of the workflow. Example: <code>ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79</code></p>
            </div>
                    </form>

                    <h2 id="workflows-DELETEapi-workflows--workflow_id-">Delete A Workflow</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-workflows--workflow_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "https://e3be6436e560.ngrok-free.app/api/workflows/ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://e3be6436e560.ngrok-free.app/api/workflows/ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-workflows--workflow_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-workflows--workflow_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-workflows--workflow_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-workflows--workflow_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-workflows--workflow_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-workflows--workflow_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-workflows--workflow_id-" data-method="DELETE"
      data-path="api/workflows/{workflow_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-workflows--workflow_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-workflows--workflow_id-"
                    onclick="tryItOut('DELETEapi-workflows--workflow_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-workflows--workflow_id-"
                    onclick="cancelTryOut('DELETEapi-workflows--workflow_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-workflows--workflow_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/workflows/{workflow_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-workflows--workflow_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-workflows--workflow_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>workflow_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="workflow_id"                data-endpoint="DELETEapi-workflows--workflow_id-"
               value="ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79"
               data-component="url">
    <br>
<p>The ID of the workflow. Example: <code>ec0d9ad0-8ab5-4c2f-a6bd-5972979a3c79</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
