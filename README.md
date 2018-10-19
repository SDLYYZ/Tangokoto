# Tangokoto
A hitokoto-like collection of Mr.Tang's .  
`master` branch and API are maintained by [Mountain & River Online Judge](https://mr.imvictor.tech/).
## Pull Request
It's recommended to make a pull request when you just heard of a new quotation of Mr.Tang's.  
The `Tangokoto` API which maintained by [Mountain & River Online Judge](https://mr.imvictor.tech/) will try to sync Tangokoto database with this repository every 30 minutes or manually.
## Usage
### How to use it in terminal?
Just copy this to your `.bashrc` or `.zshrc` and more.
```bash
echo `echo -e "\n" && wget -O - 'https://api.qwq.ren/php-api/v7/tangokoto?plain=1' 2> /dev/null && echo -e "\n"`
```

### How to deploy it to my blog.
It is recommended to use AJAX. By default, it returns a piece of JSON data. 
If your site is built with `jQuery`, you can use this to anywhere you what to get Tangokoto.
```html
<div class="ui centered inline loader" id="hitokoto-loader"></div>
<script>
  $.get('https://api.qwq.ren/php-api/v7/tangokoto', function (data) {
    if (typeof data === 'string') data = JSON.parse(data);
    $('#hitokoto-content').css('display', '').text(data.hitokoto);
    if (data.from) {
      $('#hitokoto-from').css('display', '').text('—— ' + data.from);
    }
  });
</script>
<div style="font-size: 1em; line-height: 1.5em;" id="hitokoto-content"></div>
<div style="text-align: right; margin-top: 15px; font-size: 0.9em; color: rgb(102, 102, 102);" id="hitokoto-from"></div>
```
If your site is built without `jQuery`, we also provide a external dynamic JavaScript, just import it and call `Tangokoto()` where you want Tangokoto to be shown. The url is `https://api.qwq.ren/php-api/v7/tangokoto?js=1`.
