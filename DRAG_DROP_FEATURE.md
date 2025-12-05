# Drag and Drop Feature Documentation

## Overview

The todo app now includes enhanced drag and drop functionality that allows users to easily move tasks between columns using intuitive drag and drop gestures.

## Features

### 1. **Task Drag and Drop**

-   **Click and Drag**: Users can click on any task card and drag it to another column
-   **Visual Feedback**:
    -   The dragged task becomes semi-transparent
    -   A ghost element follows the mouse cursor showing a preview of the task
    -   The target column highlights when hovering over it
    -   Smooth animations indicate successful task movement

### 2. **Auto-Scroll Functionality**

-   When dragging a task near the edges of the board, the board automatically scrolls horizontally
-   This allows users to move tasks to columns that aren't currently visible on screen

### 3. **Drag Handle**

-   Each task has a drag handle (grip icon) on the left side
-   Users can grab the task by any area, but the grip icon clearly indicates it's draggable

### 4. **Visual Indicators**

-   **Dragging State**: Task opacity reduced, subtle shadow effect
-   **Drag Over**: Target column gets highlighted with blue border and light background
-   **Success Animation**: Task shows green background flash after successful move
-   **Smooth Transitions**: All animations use CSS transitions for smooth visual feedback

## How to Use

### Moving a Task

1. Hover over a task card to see available actions
2. Click and hold on the task (anywhere on the card)
3. Drag the task to your desired column
4. Drop the task by releasing the mouse button
5. The task automatically updates and moves to the new column

### Visual Cues While Dragging

-   **Current Task**: Appears semi-transparent while being dragged
-   **Ghost Image**: A preview of the task follows your cursor
-   **Target Column**: Highlights with a blue glow when you hover over it
-   **Hover Position**: The task will insert at the position indicated by the cursor

### Browser Compatibility

-   Works on all modern browsers (Chrome, Firefox, Safari, Edge)
-   Uses native HTML5 Drag and Drop API for reliability
-   Fallback styling for older browsers

## Styling Classes

### CSS Classes Added

```css
.dragging              /* Active dragging state */
/* Active dragging state */
.drag-ghost            /* Ghost element preview */
.drag-source           /* Source column during drag */
.drag-over             /* Target column highlight */
.task-moved; /* Success animation */
```

### Customization

All drag and drop styles are in `/resources/views/layouts/app.blade.php` under the "DRAG & DROP STYLES" section. You can customize:

-   Colors and opacity
-   Animation speeds
-   Hover effects
-   Ghost element styling

## How It Works

### Backend Integration

The drag and drop system integrates with your existing:

-   `TaskController@updateColumn` endpoint for updating task positions
-   CSRF token protection for security
-   AJAX requests for seamless updates without page reload

### JavaScript Implementation

The implementation uses:

-   **Native HTML5 Drag and Drop API**: Standard browser API for drag operations
-   **AJAX Calls**: Asynchronous requests to update the database
-   **Event Listeners**: Capture drag start, over, leave, drop, and end events
-   **DOM Manipulation**: Dynamically reorder tasks in the DOM

### Database Updates

When a task is dropped in a new column:

1. The UI updates immediately for instant feedback
2. An AJAX request is sent to `/tasks/{id}/update-column`
3. The backend updates the `column_id` in the database
4. The page syncs with the server state

## Performance Optimizations

1. **Ghost Element**: Only one ghost element exists at a time, minimizing memory usage
2. **Event Delegation**: Uses document-level event listeners instead of per-element listeners
3. **CSS Transitions**: Leverages GPU acceleration for smooth animations
4. **Efficient DOM Queries**: Caches element references during drag operations

## Troubleshooting

### Tasks Not Moving

-   Check browser console for JavaScript errors
-   Ensure CSRF token is present in the page
-   Verify the `/tasks/{id}/update-column` endpoint is accessible

### Drag and Drop Not Working

-   Clear browser cache and reload
-   Check if JavaScript is enabled
-   Try a different browser to isolate browser-specific issues

### Slow Drag Performance

-   Close unnecessary browser tabs
-   Clear browser cache
-   Disable browser extensions that might interfere
-   Try a different browser

## Future Enhancements

Possible improvements for future versions:

-   Drag tasks within the same column to reorder
-   Drag columns to reorder them
-   Multi-select drag operations
-   Keyboard shortcuts for power users
-   Touch support for mobile devices
-   Animation customization options
-   Undo/Redo functionality

## API Reference

### Update Column Endpoint

```
POST /tasks/{taskId}/update-column
```

**Parameters:**

-   `column_id`: (required) The ID of the target column

**Response:**

```json
{
    "success": true,
    "message": "Task column updated successfully"
}
```

## Files Modified

1. **`resources/views/layouts/app.blade.php`**

    - Added drag and drop CSS styles

2. **`resources/views/tasks/index.blade.php`**

    - Added drag and drop JavaScript functionality

3. **`resources/js/drag-drop.js`**
    - Standalone drag and drop module (optional alternative)

## Support

For issues or questions about the drag and drop feature, please check:

-   Browser developer console for errors
-   Network tab to verify AJAX requests
-   Task's column_id in the database to confirm updates
