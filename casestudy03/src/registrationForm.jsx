import React from 'react';

const RegistrationForm = ({ formData, handleChange, errors }) => {
  return (
    <>
      {/* Username Input */}
      <div className="mb-3">
        <label htmlFor="username" className="form-label">Username*</label>
        <input
          type="text"
          className={`form-control ${errors.username ? 'is-invalid' : ''}`}
          id="username"
          name="username"
          placeholder='Eg. user1'
          value={formData.username}
          onChange={handleChange}
        />
        {errors.username && <div className="invalid-feedback">{errors.username}</div>}
      </div>

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
        <label htmlFor="password" className="form-label2">Must contain at least 8 characters</label>
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

      {/* Confirm Password Input */}
      <div className="mb-3">
        <label htmlFor="confirmPassword" className="form-label">Confirm Password*</label>
        <label htmlFor="confirmPassword" className="form-label2">Must contain at least 8 characters</label>
        <input
          type="password"
          className={`form-control ${errors.confirmPassword ? 'is-invalid' : ''}`}
          id="confirmPassword"
          name="confirmPassword"
          value={formData.confirmPassword}
          onChange={handleChange}
        />
        {errors.confirmPassword && <div className="invalid-feedback">{errors.confirmPassword}</div>}
      </div>
    </>
  );
};

export default RegistrationForm;
