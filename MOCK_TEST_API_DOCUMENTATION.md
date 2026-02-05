# Mock Test API Documentation

This document outlines the Mock Test APIs created for the PrepPoint application. All APIs are prefixed with `/api/v1/` and return JSON responses.

## Base URL
```
http://your-domain.com/api/v1/
```

## Authentication
Currently, these APIs are public and do not require authentication. Add authentication middleware if needed for production.

## API Endpoints

### 1. Get All Exams
**Endpoint:** `GET /exams`

**Description:** Retrieves a list of all available mock exams.

**Response Format:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "SSC CGL 2024",
      "slug": "ssc-cgl-2024",
      "description": "Staff Selection Commission Combined Graduate Level Exam",
      "total_papers": 5,
      "is_active": true,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

**Example Call:**
```javascript
// Using fetch
fetch('/api/v1/exams')
  .then(response => response.json())
  .then(data => console.log(data));

// Using axios
axios.get('/api/v1/exams')
  .then(response => console.log(response.data));
```

---

### 2. Get Papers for an Exam
**Endpoint:** `GET /exams/{examSlug}/papers`

**Description:** Retrieves all papers for a specific exam.

**Parameters:**
- `examSlug` (string): The slug of the exam (e.g., "ssc-cgl-2024")

**Response Format:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "exam_id": 1,
      "name": "SSC CGL Tier 1 - Paper 1",
      "slug": "ssc-cgl-tier-1-paper-1",
      "description": "General Intelligence and Reasoning",
      "total_questions": 25,
      "duration_minutes": 60,
      "max_marks": 50,
      "is_active": true,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z",
      "exam": {
        "id": 1,
        "name": "SSC CGL 2024",
        "slug": "ssc-cgl-2024"
      }
    }
  ]
}
```

**Example Call:**
```javascript
// Get papers for SSC CGL exam
fetch('/api/v1/exams/ssc-cgl-2024/papers')
  .then(response => response.json())
  .then(data => console.log(data));

// Using axios
axios.get('/api/v1/exams/ssc-cgl-2024/papers')
  .then(response => console.log(response.data));
```

---

### 3. Check Answer
**Endpoint:** `POST /questions/{questionId}/check`

**Description:** Validates a user's answer for a specific question and returns correctness with explanation.

**Parameters:**
- `questionId` (integer): The ID of the question

**Request Body:**
```json
{
  "selected_option": "A"
}
```

**Response Format (Correct Answer):**
```json
{
  "success": true,
  "data": {
    "is_correct": true,
    "correct_option": "A",
    "explanation": "This is the correct answer because...",
    "question": {
      "id": 1,
      "question_text": "What is the capital of India?",
      "subject": "General Knowledge",
      "topic": "Geography"
    }
  }
}
```

**Response Format (Incorrect Answer):**
```json
{
  "success": true,
  "data": {
    "is_correct": false,
    "correct_option": "B",
    "user_selected": "A",
    "explanation": "The correct answer is B because...",
    "question": {
      "id": 1,
      "question_text": "What is the capital of India?",
      "subject": "General Knowledge",
      "topic": "Geography"
    }
  }
}
```

**Example Call:**
```javascript
// Check answer for question ID 123
fetch('/api/v1/questions/123/check', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    selected_option: 'A'
  })
})
.then(response => response.json())
.then(data => console.log(data));

// Using axios
axios.post('/api/v1/questions/123/check', {
  selected_option: 'A'
})
.then(response => console.log(response.data));
```

---

## Error Responses

All APIs return errors in the following format:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### Common HTTP Status Codes:
- `200`: Success
- `400`: Bad Request (validation errors)
- `404`: Not Found
- `500`: Internal Server Error

---

## Data Models

### MockExam
```json
{
  "id": "integer",
  "name": "string",
  "slug": "string",
  "description": "string",
  "total_papers": "integer",
  "is_active": "boolean",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### MockExamPaper
```json
{
  "id": "integer",
  "exam_id": "integer",
  "name": "string",
  "slug": "string",
  "description": "string",
  "total_questions": "integer",
  "duration_minutes": "integer",
  "max_marks": "integer",
  "is_active": "boolean",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### MockQuestion
```json
{
  "id": "integer",
  "subject_id": "integer",
  "topic_id": "integer",
  "subtopic_id": "integer",
  "question_text": "string",
  "correct_option": "string (A/B/C/D)",
  "explanation": "string",
  "difficulty_level": "string",
  "marks": "integer",
  "is_active": "boolean",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

---

## Frontend Integration Examples

### React Hook Example
```javascript
import { useState, useEffect } from 'react';
import axios from 'axios';

function MockTestComponent() {
  const [exams, setExams] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Fetch all exams
    axios.get('/api/v1/exams')
      .then(response => {
        if (response.data.success) {
          setExams(response.data.data);
        }
      })
      .catch(error => console.error('Error fetching exams:', error))
      .finally(() => setLoading(false));
  }, []);

  const checkAnswer = async (questionId, selectedOption) => {
    try {
      const response = await axios.post(`/api/v1/questions/${questionId}/check`, {
        selected_option: selectedOption
      });

      if (response.data.success) {
        const result = response.data.data;
        console.log('Answer is correct:', result.is_correct);
        console.log('Explanation:', result.explanation);
        return result;
      }
    } catch (error) {
      console.error('Error checking answer:', error);
    }
  };

  return (
    <div>
      {loading ? (
        <p>Loading exams...</p>
      ) : (
        <ul>
          {exams.map(exam => (
            <li key={exam.id}>
              <h3>{exam.name}</h3>
              <p>{exam.description}</p>
              <p>Total Papers: {exam.total_papers}</p>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}

export default MockTestComponent;
```

### Vue.js Example
```javascript
<template>
  <div>
    <div v-if="loading">Loading exams...</div>
    <div v-else>
      <div v-for="exam in exams" :key="exam.id">
        <h3>{{ exam.name }}</h3>
        <p>{{ exam.description }}</p>
        <p>Total Papers: {{ exam.total_papers }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      exams: [],
      loading: true
    };
  },
  async mounted() {
    try {
      const response = await axios.get('/api/v1/exams');
      if (response.data.success) {
        this.exams = response.data.data;
      }
    } catch (error) {
      console.error('Error fetching exams:', error);
    } finally {
      this.loading = false;
    }
  },
  methods: {
    async checkAnswer(questionId, selectedOption) {
      try {
        const response = await axios.post(`/api/v1/questions/${questionId}/check`, {
          selected_option: selectedOption
        });

        if (response.data.success) {
          const result = response.data.data;
          console.log('Answer is correct:', result.is_correct);
          console.log('Explanation:', result.explanation);
          return result;
        }
      } catch (error) {
        console.error('Error checking answer:', error);
      }
    }
  }
};
</script>
```

---

## Testing the APIs

You can test these APIs using tools like:

1. **Postman/Insomnia**: Import the API endpoints and test requests
2. **Browser Developer Tools**: Use fetch in console
3. **cURL commands**:

```bash
# Get all exams
curl -X GET "http://localhost:8000/api/v1/exams"

# Get papers for an exam
curl -X GET "http://localhost:8000/api/v1/exams/ssc-cgl-2024/papers"

# Check answer
curl -X POST "http://localhost:8000/api/v1/questions/123/check" \
  -H "Content-Type: application/json" \
  -d '{"selected_option": "A"}'
```

---

## Database Tables Used

The APIs interact with the following MySQL tables:
- `mock_exams`
- `mock_exam_papers`
- `mock_subjects`
- `mock_topics`
- `mock_subtopics`
- `mock_questions`
- `mock_question_options`
- `mock_paper_questions`

All tables should already exist in your MySQL database as per the initial setup.

---

## Notes

1. All responses include a `success` boolean field to indicate operation status
2. Error responses include detailed error messages and validation errors
3. All datetime fields are in ISO 8601 format
4. Boolean fields are properly cast in the Eloquent models
5. Input validation is implemented for all endpoints
6. CORS headers should be configured for frontend access

For any questions or issues with these APIs, please refer to the backend controller implementations in `app/Http/Controllers/Api/`.
