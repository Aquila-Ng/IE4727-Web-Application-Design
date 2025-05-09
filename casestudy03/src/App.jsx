import { useState } from 'react';
import RegistrationForm from './registrationForm';
import LoginForm from './loginForm';
import './App.css';

const App = () => {
  const [formData, setFormData] = useState({
    username: '',
    email: '',
    password: '',
    confirmPassword: ''
  });

  const [errors, setErrors] = useState({});
  const [isSignUp, setIsSignUp] = useState(true);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const validateForm = () => {
    const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    let formErrors = {};
    
    if (isSignUp) {
      if (!formData.username) formErrors.username = 'Username is required';
      if (!formData.email) formErrors.email = 'Email is required';
      else if (!/\S+@\S+\.\S+/.test(formData.email)) formErrors.email = 'Email is invalid';

      if (!formData.password) formErrors.password = 'Password is required';
      else {
        let errors = '';
        if (!/(?=.*?[A-Z])/.test(formData.password)) errors += '- One upper case English letter\n';
        if (!/(?=.*?[a-z])/.test(formData.password)) errors += '- One lower case English letter\n';
        if (!/(?=.*?[0-9])/.test(formData.password)) errors += '- One digit\n';
        if (!/(?=.*?[#?!@$%^&*-.])/.test(formData.password)) errors += '- One special character\n';
        if (formData.password.length < 8) errors += '- 8 or more characters\n';
        if (errors) formErrors.password = 'Password must contain:\n' + errors;
      }
      if (formData.password !== formData.confirmPassword) formErrors.confirmPassword = 'Passwords do not match';
    } else {
      if (!formData.email) formErrors.email = 'Email is required';
      if (!formData.password) formErrors.password = 'Password is required';
    }

    return formErrors;
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const formErrors = validateForm();
    if (Object.keys(formErrors).length === 0) {
      // Handle successful form submission (e.g., send data to the server)
      alert('Success');
      console.log('Form submitted:', formData);
    } else {
      setErrors(formErrors);
    }
  };

  return (
    <div className="container">
      <div className="card">
        <div className="card-body">
          <h2 className="card-title">{isSignUp ? 'Registration' : 'Login'}</h2>
          <button 
            className="btn btn-secondary mb-3" 
            onClick={() => setIsSignUp(!isSignUp)}
          >
            {isSignUp ? 'Switch to Login' : 'Switch to Registration'}
          </button>
          <form onSubmit={handleSubmit}>
            {isSignUp ? (
              <RegistrationForm formData={formData} handleChange={handleChange} errors={errors} />
            ) : (
              <LoginForm formData={formData} handleChange={handleChange} errors={errors} />
            )}
            <div className="">
              <button type="submit" className="btn btn-primary">{isSignUp ? 'Register' : 'Login'}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default App;
