import { configureStore } from '@reduxjs/toolkit'
import coreReducer from './slices/CoreSlice'

/**
 * register reducers collection
 */
const store = configureStore({
  reducer: {
    medlightCore: coreReducer,
  },
})

export default store;