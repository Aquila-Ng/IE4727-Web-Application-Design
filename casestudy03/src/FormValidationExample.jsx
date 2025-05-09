import { useState } from "react";

import './App.css'

export const FormValidationExample = () => {
    const [formData, setFormData] = useState({
        username: '',
        email: '',
        password: '',
        confirmPassword: ''
    })
}
