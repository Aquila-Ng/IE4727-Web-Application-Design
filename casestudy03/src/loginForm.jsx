import React from 'react';

const LoginForm = ({ formData, handleChange, errors }) => {
  return (
    <>
      {/* Email Input */}
      <div className="mb-3">
        <label htmlFor="email" className="form-label">Email*</label>
        <input
          type="email"
          className={`form-control ${errors.email ? 'is-invalid' : ''}`}
          id="email"
          name="email"
          placeholder='Eg. abcd@123.com'
          value={formData.email}
          onChange={handleChange}
        />
        {errors.email && <div className="invalid-feedback">{errors.email}</div>}
      </div>

      {/* Password Input */}
      <div className="mb-3">
        <label htmlFor="password" className="form-label">Password*</label>
        <input
          type="password"
          className={`form-control ${errors.password ? 'is-invalid' : ''}`}
          id="password"
          name="password"
          value={formData.password}
          onChange={handleChange}
        />
        {errors.password && <div className="invalid-feedback">{errors.password}</div>}
      </div>
    </>
  );
};

export default LoginForm;
