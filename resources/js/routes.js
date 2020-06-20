// import AllBooks from './components/AllBooks.vue';
// import ViewBooks from './components/ViewBooks.vue';

// import AddClass from './components/AddClass.vue';

// import EditBook from './components/EditBook.vue';

// Lessons
import AddLessons from './components/AddLessons.vue';
import EditLessons from './components/EditLessons.vue';
import ViewLessons from './components/ViewLessons.vue';

// Subjects
import ViewSubjects from './components/ViewSubjects.vue';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: ViewLessons
    },
    {
        name: 'add-lesson',
        path: '/lessons/add',
        component: AddLessons
    },
    {
        name: 'edit-lesson',
        path: '/lessons/edit',
        component: EditLessons
    },
    {
        name: 'subjects',
        path: '/subjects',
        component: ViewSubjects
    },
];