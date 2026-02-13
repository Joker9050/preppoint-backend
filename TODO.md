# TODO - Update MCQs Filter Dropdown UI

## Task
Update the filter dropdown UI on the MCQs page (http://127.0.0.1:8000/mcqs) to match the style of the Subtopics page (http://127.0.0.1:8000/subtopics)

## Plan

### 1. Update Filter Section in resources/views/admin/mcqs/index.blade.php
- [x] Change filter container to match subtopics style (add header "Filter MCQs")
- [x] Update select dropdown classes from blue to indigo theme
- [x] Update filter button from blue to indigo
- [x] Update clear button styling to match subtopics (white bg with border)

### Changes made:
- Container: Added white shadow rounded-lg with header like subtopics
- Select elements: Changed `focus:ring-blue-500 focus:border-blue-500` to `focus:ring-indigo-500 focus:border-indigo-500`
- Filter button: Changed `bg-blue-600 hover:bg-blue-700` to `bg-indigo-600 hover:bg-indigo-700`
- Clear button: Changed from gray to white with border styling

## Status: Completed ✅
