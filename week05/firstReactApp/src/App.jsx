// import { useState } from 'react';
// // import 'bootstrap/dist/css/bootstrap.min.css';
// import './App.css'

// const FormValidationExample = () => {
//   const [formData, setFormData] = useState({
//     username: '',
//     email: '',
//     password: '',
//     confirmPassword: ''
//   });

//   const [errors, setErrors] = useState({});
//   const [isSignUp, setIsSignUp] = useState(true);

//   const handleChange = (e) => {
//     const { name, value } = e.target;
//     setFormData({ ...formData, [name]: value });
//   };

//   const validateForm = () => {
//     const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
//     let formErrors = {};
//     if (!formData.username) formErrors.username = 'Username is required';
//     if (!formData.email) formErrors.email = 'Email is required';
//     else if (!/\S+@\S+\.\S+/.test(formData.email)) formErrors.email = 'Email is invalid';

//     if (!formData.password) formErrors.password = 'Password is required';
//     else if (formData.password){
//       let errors = '';
//       if (!/(?=.*?[A-Z])/.test(formData.password))
//       {
//         errors += '- One upper case english letter \n'
//       }
//       if (!/(?=.*?[a-z])/.test(formData.password))
//       {
//         errors += '- One lower case english letter \n'
//       }
//       if (!/(?=.*?[0-9])/.test(formData.password))
//       {
//         errors += '- One digit \n'
//       }
//       if (!/(?=.*?[#?!@$%^&*-.])/.test(formData.password))
//       {
//         errors += '- One special character \n'
//       }
//       if (formData.password.length < 8)
//       {
//         errors += '- 8 or More Characters'
//       }
//       if (errors != ''){
//         formErrors.password = 'Password must contain: \n' + errors;
//       }
//     }
//     if (formData.password !== formData.confirmPassword) formErrors.confirmPassword = 'Passwords do not match';
//     return formErrors;
//   };

//   const handleSubmit = (e) => {
//     e.preventDefault();
//     const formErrors = validateForm();
//     if (Object.keys(formErrors).length === 0) {
//       // Handle successful form submission (e.g., send data to the server)
//       alert('Success');
//       console.log('Form submitted:', formData);
//     } else {
//       setErrors(formErrors);
//     }
//   };

//   return (
//     <div className="container">
//       <div className="card">
//         <div className="card-body">
//           <h2 className="card-title">Registration</h2>
//           <div>

//           </div>
//           <form onSubmit={handleSubmit}>
//             {/* Username Input */}
//             <div className="mb-3">
//               <label htmlFor="username" className="form-label">Username*</label>
//               <input
//                 type="text"
//                 className={`form-control ${errors.username ? 'is-invalid' : ''}`}
//                 id="username"
//                 name="username"
//                 placeholder='Eg. user1'
//                 value={formData.username}
//                 onChange={handleChange}
//               />
//               {errors.username && <div className="invalid-feedback">{errors.username}</div>}
//             </div>

//             {/* Email Input */}
//             <div className="mb-3">
//               <label htmlFor="email" className="form-label">Email*</label>
//               <input
//                 type="email"
//                 className={`form-control ${errors.email ? 'is-invalid' : ''}`}
//                 id="email"
//                 name="email"
//                 placeholder='Eg. abcd@123.com'
//                 value={formData.email}
//                 onChange={handleChange}
//               />
//               {errors.email && <div className="invalid-feedback">{errors.email}</div>}
//             </div>

//             {/* Password Input */}
//             <div className="mb-3">
//               <label htmlFor="password" className="form-label">Password*</label>
//               <label htmlFor="password" className="form-label2">Must contain at least 8 characters</label>
//               <input
//                 type="password"
//                 className={`form-control ${errors.password ? 'is-invalid' : ''}`}
//                 id="password"
//                 name="password"
//                 value={formData.password}
//                 onChange={handleChange}
//               />
//               {errors.password && <div className="invalid-feedback">{errors.password}</div>}
//             </div>

//             {/* Confirm Password Input */}
//             <div className="mb-3">
//               <label htmlFor="confirmPassword" className="form-label">Confirm Password*</label>
//               <label htmlFor="confirmPassword" className="form-label2">Must contain at least 8 characters</label>
//               <input
//                 type="password"
//                 className={`form-control ${errors.confirmPassword ? 'is-invalid' : ''}`}
//                 id="confirmPassword"
//                 name="confirmPassword"
//                 value={formData.confirmPassword}
//                 onChange={handleChange}
//               />
//               {errors.confirmPassword && <div className="invalid-feedback">{errors.confirmPassword}</div>}
//             </div>

//             {/* Submit Button */}
//             <div className="">
//               <button type="submit" className="btn btn-primary">Register</button>
//             </div>
//           </form>
//         </div>
//       </div>
//     </div>
//   );
// };

// export default FormValidationExample;
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
