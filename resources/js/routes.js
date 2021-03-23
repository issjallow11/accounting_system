export default [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/creditAccount', component: require('./components/transactions/Credit.vue').default },
    { path: '/debitAccount', component: require('./components/transactions/Debit.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/products', component: require('./components/product/Products.vue').default },
    { path: '/accounts', component: require('./components/Accounts/Accounts.vue').default },
    { path: '/subAccounts', component: require('./components/Accounts/SubAccount.vue').default },
    { path: '/product/tag', component: require('./components/product/Tag.vue').default },
    { path: '/product/category', component: require('./components/product/Category.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default }
];
