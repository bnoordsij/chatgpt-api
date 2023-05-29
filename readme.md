# ChatGPT API

This is an outline of how to use the ChatGPT API.

Instead of just generating text output, ChatGPT can return any type of content you like
I have experimented with returning JSON and CSV, but it can also return HTML, XML, or any other format you like.

Focussing on JSON for now, I can give an example data structure that the output should match.

### API
I am using the chatGPT API, there might be better APIs out there for this purpose, but the results I get so far work for me


### Usage
To create a new API, you need to extend the Endpoint class, provide a basic description of what the API should return and a data structure, the structure follows the Laravel validation rules

```php
class QuestionEndpoint extends Endpoint
{
    protected string $plural = 'questions';
    
    protected string $apiDescription = 'This API is specialized in generating questions about Dutch history written in Dutch';
    
    protected array $showStructure = [
        'title' => 'string|max:100',
        'description' => 'nullable|string|min:100|max:500',
        'difficulty' => 'enum(easy,medium,hard)',
        'options' => [
            [
                'title' => 'string|max:30',
                'is_correct' => 'boolean',
            ],
        ],
    ];
    
    protected array  $listStructure = [
        'title' => 'string|max:100',
    ];
}
```

#### Use the list method
```php
$endpoint = new QuestionEndpoint();
$questions = $endpoint->list(50);
```

#### Use the show method
```php
$endpoint = new QuestionEndpoint();
$question = $endpoint->show();
```

### Limitations
The API output is cut off around 1000 characters, also your request might time out, because it is generated on the fly and will take much longer

#### Reducing the response size
- Reduce number of items in the list
- Reduce fields in data structure
- Split large bodies of text into more Endpoints, like a separate QuestionDescriptionEndpoint
