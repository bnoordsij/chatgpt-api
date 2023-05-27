# ChatGPT API

This is an outline of how to use the ChatGPT API.

Instead of just generating text output, ChatGPT can return any type of content you like
I have experimented with returning JSON and CSV, but it can also return HTML, XML, or any other format you like.

Focussing on JSON for now, I can give an example data structure that the output should match.

Suppose I have the question:

What is the capital of France?
1. France
2. Paris
3. Lion
4. London

and I want to create a new question: What is the capital of Germany?

I can pass the structure of the previous question to the API to have the response match that same structure

```json
{
    "question": "What is the capital of France?",
    "options": [
        "France",
        "Paris",
        "Lion",
        "London"
    ],
    "answer": "Paris"
}
```

### API
I am using the chatGPT API, there might be better APIs out there for this purpose, but the results I get so far work for me



### Api usage
I am not clear yet on how to pass the data structure
I want either a toResource method on the Question model, or a WrapperQuestion class in which the structure and output is defined

